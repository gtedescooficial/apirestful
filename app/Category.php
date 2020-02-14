<?php

namespace App;

use App\Product;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
use SoftDeletes;


protected $hidden = [
    'pivot'
];

    public $transformer = CategoryTransformer::class;

    protected $dates = ['delete_at'];


    protected $fillable = [
        'name',
        'description'
    ];

   

    public function products(){
        return $this->belongsToMany(Product::class);
    }
}
