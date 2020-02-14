<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;

class Buyer extends User
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    public $transformer = BuyerTransformer::class;
    
    protected $table = 'users';
    
    public function transactions(){
        
        return $this->hasMany( 'App\Transaction' );
    }
}
