<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class Titles extends Controller
{
    public function all(Request $request)
    {   
        try{
            $user = $request->user();
            $titles = DB::table('titles')->where('id_user', '=', $user->id)->get();
            
            return response()->json(['Message' => 'Sucess', 'Data'=>$titles], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }

    }

    public function get($id, Request $request)
    {   
        $user = $request->user();
        $validator = Validator::make(
            [
                'id' => $id,
            ],
            [
                'id' => 'required|numeric',
            ],[
                'id.required' => 'Please enter a id',
                'id.numeric' => 'Please enter a numeric id',
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400);

        try{
            $title = DB::table('titles')
                ->where('id', '=', $id)
                ->where('id_user', '=', $user->id)->first();

            return response()->json(['Message' => 'Sucess', 'Data'=>$title], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
    }


    public function store(Request $request){

        $validator = Validator::make(
            [
                'title' => $request->title,
            ],
            [
                'title' => 'required',
            ],[
                'title.required' => 'Please enter a title',
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400);
        
        try{
            $title = new Title();
            $user = $request->user();
    
            $title->id_user = $user->id;
            $title->title = $request->title;
            $title->save();

            return response()->json(['Message' => 'Sucess', 'id' => $title->id], 200);

        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
        
    }

    public function put( $id,Request $request){

        $validator = Validator::make(
            [
                'id' => $id,
                'title' => $request->title,
            ],
            [
                'id' => 'required|numeric',
                'title' => 'required',
            ],[
                'id.required' => 'Please enter a id',
                'id.numeric' => 'Please enter a numeric id',
                'title.required' => 'Please enter a title',
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400);

        
        try{
            $user = $request->user();
            $title = DB::table('titles')
                ->where('id', '=', $id)
                ->where('id_user', '=', $user->id)
                ->first();

            if($title == null || $title == '')
                return response()->json(['Message' => 'Title not found'], 400 );

            DB::table('titles')
                ->where('id', '=', $id)
                ->where('id_user', '=', $user->id)
                ->update(array('title' => $request->title));

            return response()->json(['Message' => 'Sucess', 'id' => $id], 200 );

        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
        
    }

    public function delete($id,Request $request){

        $validator = Validator::make(
            [
                'id' => $id,          
            ],
            [
                'id' => 'required|numeric',
            ],[
                'id.required' => 'Please enter a id',
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400);
        
        try{
            $user = $request->user();
            $title = DB::table('titles')
                ->where('id', '=', $id)
                ->where('id_user', '=', $user->id)
                ->first();

            if($title == null || $title == '')
                return response()->json(['Message' => 'Title not found'], 400 );

            DB::table('titles')
                ->where('id', '=', $id)
                ->where('id_user', '=', $user->id)
                ->delete();

            return response()->json(['Message' => 'Sucess', 'id' => $id], 200 );

        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
        
    }
}
