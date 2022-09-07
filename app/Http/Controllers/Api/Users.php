<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Users extends Controller
{   

    public function store(Request $request){

        $user = new User();

        try{
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
    
            $user->save();
            return response()->json(['Message' => "Sucess", "Id" => $user->id ] , 200);

        }catch(\Exception $e){
            return response()->json(['Message' => $e ] , 400);
        }
        

    }
}
