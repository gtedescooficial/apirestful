<?php

namespace App\Http\Controllers\Product;


use App\Product;
use App\User;
use App\Transaction;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{
   
    public function __construct(){

        parent::__construct();

        //ADICIONADA FILTRADA DO MIDDLEWARE 
        $this->middleware('transform.input'.TransactionTransformer::class)->only(['store']);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity'=>'required|integer|min:1'
        ];

        $this->validate($request,$rules);

        if($buyer->id == $product->seller_id):
            return $this->errorResponse('O comprador deve ser diferente do vendedor',409);
            elseif(!$buyer->is_verified()):
                return $this->errorResponse('Operação para usuarios verificados',409);
            elseif(!$product->seller->is_verified()):
                return $this->errorResponse('Operação para vendedores com conta verificada',409);
                
            endif;
            
            if(!$product->productYes()):
                return $this->errorResponse('O Produto nao se encontra dispinivel',409);
                
            endif;
            
            if($product->quantity < $request->quantity){
                return $this->errorResponse('Quantidade de estoque insuficiente para esta operação',409);
                
            }

            DB::transaction(function () use ($request, $product, $buyer) {
                $product->quantity -= $request->quantity;
                $product->save();

                $transaction = Transaction::create([
                        'quantity' => $request->quantity,
                        'buyer_id' => $buyer->id,
                        'product_id' => $product->id,
                ]);

                return $this->showOne($transaction);
            });
    }



}
