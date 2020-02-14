<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Category;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'key' => (int)$category->id,
            'title' => (string)$category->name,
            'details' => (string)$category->description,
            'createdAt' => (string)$category->created_at,
            'updatedAt' => (string)$category->updated_at,
            'deletedAt' => isset($category->deleted_at) ? (string)$category->deleted_at : null,
            //SEGUEM ABAIXO AS HATEOAS (LINKS PARA AJUDAR O FRONTEND COM AS CONSULTAS RELACIONADAS)
         'links' => [
             [
                 'rel' => 'self',
                'href' => route('categories.show', $category->id),
            ],
            [
                'rel' => 'category.buyers' ,
                'href' => route('categories.buyers.index', $category->id),
            ],
            [
                'rel' => 'category.products' ,
                'href' => route('categories.products.index', $category->id),
            ],
            [
                'rel' => 'category.sellers' ,
                'href' => route('categories.sellers.index', $category->id),
            ],
            [
                'rel' => 'category.transactions' ,
                'href' => route('categories.transactions.index', $category->id),
            ],
         ]
        ];
    }

    public static function originalAttr($index){

        $attrs =  [
            'key' => 'id',
            'title' => 'name',
            'details' => 'description',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
            'deletedAt' => 'deleted_at'
            
            
        ];

        return isset($attrs[$index] ) ? $attrs[$index] : null;
    }
}
