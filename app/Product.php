<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    const PRODUCT_NO = 'no';
    const PRODUCT_YES = 'yes';


    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];

    public function pruductYes(){
        return $this->status == Product::PRODUCT_YES;
    }

    public function categories(){
        return $this->belongsToMany('App\Category');
    }

    public function seller(){
        return $this->belongsTo('App\Seller');
    }

    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
    
    
}
