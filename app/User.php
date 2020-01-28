<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    const ADMIN = 'true';
    const GUEST = 'false';
    
    const VERIFIED = '1';
    const UNVERIFIED = '0';

    protected $dates = ['delete_at'];

    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'verification_token','admin','admin'
    ];
    public function setNameAttribute($value){
        $this->attributes['name'] = strtolower($value);
        
    }
    
    public function setEmailAttribute($value){
        $this->attributes['email'] = strtolower($value);

    }

    public function getNameAttribute($value){
        return ucwords($value);
    }
    
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token'
    ];

    public function is_admin(){
            return $this->admin == User::ADMIN;
    }

    public function is_verified(){
        return $this->verified == User::VERIFIED;
    }

    public static function generateVerificationToken(){
        return str_random(40);
    }
}
