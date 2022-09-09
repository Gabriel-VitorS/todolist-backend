<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Users extends Controller
{   
    public function get(Request $request)   {
        return response()->json(['Message' => 'Sucess', 'Data' => $request->user()], 200);
    }


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

    public function put(Request $request)   {
        $userRequest = $request->user();
        $user = User::find($userRequest->id);

        $validator = Validator::make(
            [
                'password' => $request->password,
                'email' => $request->email,
                'name' => $request->name
            ],
            [
                'password' => 'required',
                'email' => 'required',
                'name'=> 'required'
            ],[
                'password.required' => 'Please enter a password',
                'email.required' => 'Please enter a email',
                'name.required' => 'Please enter a name'
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400);
        
        if(! Hash::check($request->password,$user->password) )
            return response()->json(['Message' => 'Invalid password'], 401);

        try{

            if($request->email != $user->email){
                DB::table('users')
                    ->where('id', '=', $userRequest->id)
                    ->update(array('email' => $request->email));
            }
            
            if($request->name != $user->name){
                DB::table('users')
                    ->where('id', '=', $userRequest->id)
                    ->update(array('name' => $request->name));
            }
    
            if($request->newPassord != '' || $request->newPassord != null){
                DB::table('users')
                    ->where('id', '=', $userRequest->id)
                    ->update(array('password' => $request->newPassord));
            }
    
            
            return response()->json(['Message' => 'Sucess'], 200);

        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suporte'], 500);
        }
        
    }

    public function delete(Request $request){
        $userRequest = $request->user();

        try{
            $user = User::find($userRequest->id);
            $user->delete();

            return response()->json(['Message' => 'Sucess'], 500);
        }catch(\Exception $e){
            return response($e);
            // return response()->json(['Message' => 'Server error. Please contact the suporte'], 500);
        }
        

    }
}
