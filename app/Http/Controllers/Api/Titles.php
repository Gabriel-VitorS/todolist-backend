<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Title;
use Illuminate\Http\Request;

class Titles extends Controller
{
    public function get(Request $request)
    {   
        $user = $request->user();
        return response()->json([$user->id], 200);
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
            return response()->json(['Message' => $e]. 400);
        }
        
    }

    public function put( $id,Request $request){

        // if(! $request->user() || $request->title == null || $request->title == '')
        //     return response(['Message' => "Invalid parameters"], 400);

        // return response()->json($id);
        
        // try{
        //     $title = new Title();
        //     $user = $request->user();
    
        //     $title->id_user = $user->id;
        //     $title->title = $request->title;
        //     $title->save();

        //     return response()->json(['Message' => 'Sucess', 'id' => $title->id], 200);

        // }catch(\Exception $e){
        //     return response()->json(['Message' => $e]. 400);
        // }
        
    }
}
