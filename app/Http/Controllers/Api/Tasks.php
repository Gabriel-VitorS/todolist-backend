<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Title;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Tasks extends Controller
{   

    /**
     * Função para pegar todos os dados do usuário
     * 
     * @return json
     */
    public function all(Request $request){
        
        $validator = Validator::make(
            [
                'id_title' => $request->id_title,
            ],
            [
                'id_title' => 'required|numeric',
            ],[
                'id_title.required' => 'Please enter a id_title',
                'id_title.numeric' => 'Please enter a numeric in id_title',
            ]);
    
        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400);
 
        try{
            $user = $request->user();

            $tasks = DB::table('tasks')
                ->where('id_user', '=', $user->id)
                ->where('id_title', '=', $request->id_title)
                ->get();

            return response()->json(['Message' => 'Sucess', 'Data' => $tasks], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
    }

    /**
     * Função para pegar um dado
     * 
     * @return json
     */
    public function get($id, Request $request)
    {
     
        $validator = Validator::make(
            [
                'id_title' => $request->id_title,
                'id' => $id
            ],
            [
                'id_title' => 'required|numeric',
                'id' => 'required|numeric'
            ],[
                'id_title.required' => 'Please enter a id_title',
                'id_title.numeric' => 'Please enter a numeric in id_title',
                'id.numeric' => 'Please enter a numeric in id'
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400);
        

        try{
            $user = $request->user();

            $task = DB::table('tasks')
                ->where('id_user', '=', $user->id)
                ->where('id_title', '=', $request->id_title)
                ->where('id', '=', $id)
                ->get();

            return response()->json(['Message' => 'Sucess', 'Data' => $task], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
    }

    /**
     * Função para insert
     * 
     * @return json
     */
    public function store(Request $request)
    {

        $rules = array(
            'id_title' => 'required',
            'id_title' => 'numeric',
            'task' => 'required',
            'status' => 'required'
        );
        $message = array(
            'id_title.required' => 'Pleaser enter a id_title',
            'id_title.numeric' => 'Pleaser enter a number in id_title',
            'task.required' => 'Please enter a task',
            'status.required' => 'Please enter a status'
        );

        $validator = Validator::make($request->all(), $rules, $message);

        if($validator->fails()){
            $messages=$validator->messages();
            $errors=$messages->all();
            return response()->json(['Message' => $errors],400);
        }

        try{
            $user = $request->user();

            $title = DB::table('titles')
                ->where('id_user', '=', $user->id)
                ->where('id', '=', $request->id_title)
                ->first();

            if($title == '' || $title == null)
                return response()->json(['Message' => 'Title not found'], 400);

    
            $task = new Task();
            $task->id_title = $request->id_title;
            $task->id_user = $user->id;
            $task->task = $request->task;
            $task->done = $request->status;
            $task->save();
    
            return response()->json(['Message' => 'Sucess', 'Id' => $task->id], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
        

    }

    /**
     * Função para update
     * 
     * @return json
     */
    public function put($id, Request $request){
        // return response()->json($request->all());
        $validator = Validator::make(
            [
                'id_title' => $request->id_title,
                'id' => $id,
                'task' => $request->task,
                'status' => $request->status
            ],
            [
                'id_title' => 'required|numeric',
                'id' => 'required|numeric',
                'task' => 'required',
                'status' => 'required'
            ],[
                'id_title.required' => 'Please enter a id_title',
                'id_title.numeric' => 'Please enter a numeric in id_title',
                'id.numeric' => 'Please enter a numeric in id',
                'task.required' => 'Please enter a task',
                'status.required' => 'Please enter a status',
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator->messages()], 400);

        try{
            $user = $request->user();

            $title = DB::table('titles')
                ->where('id_user', '=', $user->id)
                ->where('id', '=', $request->id_title)
                ->first();

            if($title == '' || $title == null)
                return response()->json(['Message' => 'Title not found'], 400);

            $task = DB::table('tasks')
                ->where('id_user', '=', $user->id)
                ->where('id_title', '=', $request->id_title)
                ->where('id', '=', $id)
                ->first();

            if($task == '' || $task == null)
                return response()->json(['Message' => 'Invalid task'], 400);

            DB::table('tasks')
                ->where('id_user', '=', $user->id)
                ->where('id_title', '=', $request->id_title)
                ->where('id', '=', $id)
                ->update(array('task' => $request->task, 'done' => $request->status));


            return response()->json(['Message' => 'Sucess', 'id' => $task->id], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
    }

    /**
     * Função para Deletar
     * 
     * @return json
     */
    public function delete($id, Request $request){

        $validator = Validator::make(
            [
                'id_title' => $request->id_title,
                'id' => $id,            ],
            [
                'id_title' => 'required|numeric',
                'id' => 'required|numeric',
            ],[
                'id_title.required' => 'Please enter a id_title',
                'id_title.numeric' => 'Please enter a numeric in id_title',
                'id.numeric' => 'Please enter a numeric in id',
                'task.required' => 'Please enter a task'
            ]);

        if($validator->fails())
            return response()->json(['Message' => $validator], 400);

        try{
            $user = $request->user();

            $title = DB::table('titles')
                ->where('id_user', '=', $user->id)
                ->where('id', '=', $request->id_title)
                ->first();

            if($title == '' || $title == null)
                return response()->json(['Message' => 'Invalid title'], 400);

            $task = DB::table('tasks')
                ->where('id_user', '=', $user->id)
                ->where('id_title', '=', $request->id_title)
                ->where('id', '=', $id)
                ->first();

            if($task == '' || $task == null)
                return response()->json(['Message' => 'Invalid task'], 400);

            DB::table('tasks')
                ->where('id_user', '=', $user->id)
                ->where('id_title', '=', $request->id_title)
                ->where('id', '=', $id)
                ->delete();


            return response()->json(['Message' => 'Sucess', 'id' => $task->id], 200);
        }catch(\Exception $e){
            return response()->json(['Message' => 'Server error. Please contact the suport'], 500);
        }
    }
}
