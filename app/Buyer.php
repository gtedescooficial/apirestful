<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{

    protected $table = 'users';
    
    public function transactions(){
        
        return $this->hasMany( 'App\Transaction' );
    }
}
