<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    protected $table = 'users';
    
     public function products(){
         return $this->hasMany('App\Product');
     }
}
