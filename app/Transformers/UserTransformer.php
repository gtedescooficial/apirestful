<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            
            'key' => (int)$user->id,
            'name' => (string)$user->name,
            'email' => (string)$user->email,
            'isVerified' => (string)$user->verified,
            'isAdmin' => ($user->admin === 'true'),
            'cratedAt' => (string)$user->created_at,
            'updatedAt' => (string)$user->updated_at,
            'deletedAt' => isset($user->deleted_at) ? (string)$user->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                   'href' => route('users.show', $user->id),
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
            'isAdmin' => 'admin',
            'cratedAt' => 'created_at',
            'updatedAt' => 'updated_at',
            'deletedAt' => 'deleted_at',
            
            
        ];

        return isset($attrs[$index] ) ? $attrs[$index] : null;
    }
}
