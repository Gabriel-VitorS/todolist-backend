<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Users extends Controller
{   
    public function login(Request $request){

        if($request->email == '' || $request->email == null || $request->password == '' || $request->password == null)
            return response()->json(['Message' => "Invalid parameters"], 400);


        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $token = $user->createToken("JWT");

            return response()->json(['Message' => 'Sucess', 'Token' => $token->plainTextToken],200);
        }


        return response()->json(['Message' => "Invalid user"], 401);
        

    }

    public function store(Request $request){

        $user = new User();

        try{
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
    
            $user->save();
            return response()->json(['Message' => "Sucess", "Id" => $user->id ] , 200);

        }catch(\Exception $e){
            return response()->json(['Message' => $e ] , 400);
        }
        

    }
}
