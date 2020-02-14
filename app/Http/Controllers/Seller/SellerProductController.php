<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\User;
use App\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Transformers\ProductTransformer;

class SellerProductController extends ApiController
{

    public function __construct(){

        parent::__construct();

        $this->middleware('transform.input'.ProductTransformer::class)->only(['store', 'update']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description'=>'required',
            'quantity'=>'required|integer|min:1',
            'image'=>'required|image',
        ];

        $this->validate($request,$rules);
        $data = $request->all();
        $data['status'] = Product::PRODUCT_NO;
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return $this->showOne($product,201);

    }

  

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            
                'quantity' => 'required|integer:min:1',
                'status' => 'in: '. Product::PRODUCT_YES .','.Product::PRODUCT_NO ,
                'image' => 'image'
        ];

        $this->validate($request,$rules);

        if($seller->id != $product->seller_id):
            $this->errorResponse('O id do vendedor deve ser igual ao id cadastrado no seller id do produto',422);
        endif;

            $product->fill($request->intersect([
                'name',
                'description',
                'quantity'
            ]));

            if($request->has('status')):
            
                $product->status = $request->status;
                if($product->productYes && $product->categories()->count() == 0):
                    return $this->errorResponse('Para que o produto esteja disponivel deve ter ao menos uma categoria',409);
                endif;
            endif;

            if($request->hasFile('image')){
                Storage::delete($product->image);
                $product->image = $request->image->store('
                ');
            }

            if($product->isClean()){
                return $this->errorResponse('Se debe especificar ao menos um valor diferente para atualizar',422);
            }

            $product->save();

            return $this->showOne($product);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        
        if($seller->id != $product->seller_id):
            $this->errorResponse('O id do vendedor deve ser igual ao id cadastrado no seller id do produto',422);
        endif;
        Storage::delete($product->image);

        $product->delete();

        $this->showOne($product);
    }
}
