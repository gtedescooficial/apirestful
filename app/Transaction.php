<?php

namespace App;

use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Transaction extends Model
{
    use Notifiable, SoftDeletes;
    protected $dates = ['delete_at'];

    public $transformer = TransactionTransformer::class;
    
    protected $fillable = [
        
        'quantity',
        'product_id',
        'buyer_id',
        
    ];

    public function buyer(){
        return $this->belongsTo('App\Buyer');
    }
    
    public function product(){
        return $this->belongsTo('App\Product');
    }

}
