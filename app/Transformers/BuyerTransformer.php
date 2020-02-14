<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Buyer;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'key' => (int)$buyer->id,
            'name' => (string)$buyer->name,
            'email' => (string)$buyer->email,
            'isVerified' => (string)$buyer->verified,
            'createdAt' => (string)$buyer->created_at,
            'updatedAt' => (string)$buyer->updated_at,
            'deletedAt' => isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                   'href' => route('buyers.show', $buyer->id),
               ],
               [
                'rel' => 'buyers.categories' ,
                'href' => route('buyers.categories.index', $buyer->id),
            ],
            [
                'rel' => 'buyer.products',
                'href' => route('buyers.products.index', $buyer->id),
            ],
            [
                'rel' => 'buyer.sellers',
                'href' => route('buyers.sellers.index', $buyer->id),
            ],
            [
                'rel' => 'buyer.transactions',
                'href' => route('buyers.transactions.index', $buyer->id),
            ],
            [
                'rel' => 'user',
                'href' => route('users.show', $buyer->id),
            ],
            ]
        ];
    }

    public static function originalAttr($index){

        $attrs =  [
            'key' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
            'deletedAt' => 'deleted_at'
            
            
        ];

        return isset($attrs[$index] ) ? $attrs[$index] : null;
    }
}
