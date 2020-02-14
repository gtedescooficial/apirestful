<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;

class Seller extends User
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    protected $table = 'users';
    public $transformer = SellerTransformer::class;
    
     public function products(){
         return $this->hasMany('App\Product');
     }
}
