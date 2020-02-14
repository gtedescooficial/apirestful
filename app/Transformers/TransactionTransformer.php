<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transaction;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'key' => (int) $transaction->id,
            'quantity' => (int) $transaction->quantity,
            'buyer' => (int) $transaction->buyer_id,
            'product' => (int) $transaction->product_id,
            'createdAt' => (string) $transaction->created_at,
            'updatedAt' => (string) $transaction->updated_at,
            'deletedAt' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                   'href' => route('transactions.show', $transaction->id),
               ],
               [
                   'rel' => 'transactions.categories' ,
                   'href' => route('transactions.categories.index', $transaction->id),
               ],
               
               [
                   'rel' => 'buyer' ,
                   'href' => route('buyer.show', $transaction->buyer_id),
               ],
               [
                   'rel' => 'product' ,
                   'href' => route('product.show', $transaction->product_id),
               ],
               [
                   'rel' => 'transactions.seller' ,
                   'href' => route('transactions.sellers.index', $transaction->id),
               ],
            ]

        ];
    }

    public static function originalAttr($index){

        $attrs =  [
            'key' => 'id',
            'quantity' => 'quantity',
            'buyer' => 'buyer_id',
            'product' => 'product_id',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
            'deletedAt' => 'deleted_at'
            
            
        ];

        return isset($attrs[$index] ) ? $attrs[$index] : null;
    }
}
