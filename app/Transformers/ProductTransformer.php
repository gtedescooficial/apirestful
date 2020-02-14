<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Product;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
               'key' => (int)$product->id,
               'title' => (string)$product->name,
               'details' => (string)$product->description,
               'quantity' => (string)$product->quantity,
               'status' => (string)$product->status,
               'img' => url("img/{$product->image}"),
               'seller' => (int)$product->seller_id,
               'createdAt' => (string)$product->created_at,
               'updatedAt' => (string)$product->updated_at,
               'deletedAt' => isset($product->deleted_at) ? (string)$product->deleted_at : null,
               'links' => [
                [
                    'rel' => 'self',
                   'href' => route('products.show', $product->id),
               ],
               [
                   'rel' => 'products.categories' ,
                   'href' => route('products.categories.index', $product->id),
               ],
               
               [
                   'rel' => 'seller' ,
                   'href' => route('seller.show', $product->seller_id),
               ],
               [
                   'rel' => 'products.transactions' ,
                   'href' => route('products.transactions.index', $product->id),
               ],
            ]

        ];
    }

    public static function originalAttr($index){

        $attrs =  [
            'key' => 'id',
            'title' => 'name',
            'details' => 'description',
            'quantity' => 'quantity',
            'status' => 'status',
            'img' => 'image',
            'seller' => 'seller_id',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
            'deletedAt' => 'deleted_at',
           
            
        ];

        return isset($attrs[$index] ) ? $attrs[$index] : null;
    }
}
