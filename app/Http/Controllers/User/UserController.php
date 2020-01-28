<?php

namespace App\Http\Controllers\User;

use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
        return $this->showAll($usuarios);
        /* $usuarios = response()->json(['data' => $usuarios], 200);
        return $usuarios; */
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            
        ];

        $this->validate($request, $rules);

        $campos = $request->all();
        
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::UNVERIFIED;
        $campos['verification_token'] = User::generateVerificationToken();
        $campos['admin'] = User::ADMIN;

        $usuario = User::create($campos);

        return $this->showOne($usuario,201);
        // return response()->json(['data'=>$usuario],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // $usuario = User::findOrFail($id);

        return $this->showOne($user);
        // return response()->json(['data' => $usuario],200);
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // $user = User::findOrFail($id);
        
         $rules = [
           
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'required|min:6',
            'admin' => 'in:'.User::ADMIN.','.User::GUEST,

            
        ]; 

        $this->validate($request, $rules);

        if( $request->has('email') && $user->email != $request->email ):
            
            
            $user->verified = User::UNVERIFIED;
           $user->verification_token = User::generateVerificationToken();
            $user->email = $request->email; 
    
        endif; 

        if( $request->has('password') ):
            $user->password = bcrypt($request->password);
        endif;

        if( $request->has('name') ):
            $user->name = $request->name;
        endif;

        if( !$user->isDirty() ):
            return $this->errorResponse('É necessario especificar algum valor diferente do original',422);
            // return response()->json(['error'=>'É necessario especificar algum valor diferente do original', 'code' => 422],422);    
        endif;

        if( $request->has('admin') ):
            if(! $user->is_verified() ):
                return $this->errorResponse('Unicamente usuarios com conta verificada podem virar admin',409);

                // return response()->json(['error'=>'Unicamente usuarios com conta verificada podem virar admin', 'code'=>409],409);
            endif;

            $user->admin = $request->admin;


        endif;


    $user->save();
    return $this->showOne($user);

    // return response()->json(['data'=>$user],200); 


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        
        $user->delete();
        return $this->showOne($user,201);
        // return response()->json(['data' => $user,'code'=>'Usuario eliminado com sucesso'],200);
    }
}
