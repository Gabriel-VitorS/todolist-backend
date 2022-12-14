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

        $validator = Validator::make(
            [
                'email' => $request->email,     
                'password' => $request->password,          
            ],
            [
                'email' => 'required',
                'password' => 'required',
            ],[
                'email.required' => 'Please enter a email',
                'password.required' => 'Please enter a password',
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400); 

        try{
            $credentials = $request->only('email', 'password');

            if(Auth::attempt($credentials)){
                $user = Auth::user();
                $token = $user->createToken("JWT");

                return response()->json(['Message' => 'Sucess', 'Token' => $token->plainTextToken],200);
            }

            return response()->json(['Message' => "Invalid user"], 400);

        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }

        
        

    }

    public function store(Request $request){

        $validator = Validator::make(
            [
                'email' => $request->email,     
                'password' => $request->password,  
                'name' => $request->name        
            ],
            [
                'email' => 'required',
                'password' => 'required',
                'name' => 'required'
            ],[
                'email.required' => 'Please enter a email',
                'password.required' => 'Please enter a password',
                'name.required' => 'Please enter a password',
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400); 

        try{
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
    
            $user->save();
            return response()->json(['Message' => "Sucess", "Id" => $user->id ] , 200);

        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
        

    }


    public function checkEmail(Request $request){
        try{
            $email = DB::table('users')
                ->where('email', '=', $request->email)->first();

            //Return 0 or 1.
            //If return 0 the email is valid
            if($email == '')
                return response()->json(['email' => '1'], 200);
            else{
                return response()->json(['email' => '0'], 200);
            }
        }catch(\Exception){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
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
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
        
    }

    public function delete(Request $request){
        $userRequest = $request->user();

        try{
            $user = User::find($userRequest->id);
            $user->delete();

            return response()->json(['Message' => 'Sucess'], 500);
        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
        

    }
}
