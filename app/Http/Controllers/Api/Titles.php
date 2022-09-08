<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Titles extends Controller
{
    public function all(Request $request)
    {   
        try{
            $user = $request->user();
            $titles = DB::table('titles')->where('id_user', '=', $user->id)->get();
            
            return response()->json(['Message' => 'Sucess', 'Data'=>$titles], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => $e], 400);
        }

    }

    public function get($id, Request $request)
    {
        try{
            $title = Title::find($id);
            return response()->json(['Message' => 'Sucess', 'Data'=>$title], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => $e], 400);
        }
    }


    public function store(Request $request){

        if(! $request->user() || $request->title == null || $request->title == '')
            return response(['Message' => "Invalid parameters"], 400);
        
        try{
            $title = new Title();
            $user = $request->user();
    
            $title->id_user = $user->id;
            $title->title = $request->title;
            $title->save();

            return response()->json(['Message' => 'Sucess', 'id' => $title->id], 200);

        }catch(\Exception $e){
            return response()->json(['Message' => $e], 400);
        }
        
    }

    public function put( $id,Request $request){

        if(! $request->user() || $request->title == null || $request->title == '')
            return response(['Message' => "Invalid parameters"], 400);

        
        try{
            $title = Title::find($id);
            $title->title = $request->title;
            $title->save();

            return response()->json(['Message' => 'Sucess', 'id' => $title->id], 200 );

        }catch(\Exception $e){
            return response()->json(['Message' => $e], 400);
        }
        
    }

    public function delete($id,Request $request){

        if(! $request->user())
            return response(['Message' => "Invalid parameters"], 400);

        
        try{
            $title = Title::find($id);
            $title->delete();

            return response()->json(['Message' => 'Sucess', 'id' => $title->id], 200 );

        }catch(\Exception $e){
            return response()->json(['Message' => $e], 400);
        }
        
    }
}
