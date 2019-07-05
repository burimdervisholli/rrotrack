<?php

namespace App\Http\Controllers;

use Task;
use Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $validator = $this->valdator($request->all());

        if($validator->fails())
        {
            return response()->json([
                'success'   => false,
                'errors'    => $validator->errors()->all()
            ], 422);
        }
        else
        {
            $task = Task::create([
                'title'       => $request['title'],
                'description' => $request['description'],
                'image'       => $request['image'],
                'estimate'    => $request['estimate'],
                'user_id'     => $request['user_id'],
                'role_id'     => $request['role_id'],
                'column_id'   => $request['column_id'],
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Task created successfully.',
                'task'    => $task,
            ], 200);
        }   
    }

    public function edit(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        if($task)
        {
            $validator = $this->validator($request->all());

            if($validator->fails())
            {
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors()->all()
                ], 422);
            }
            else
            {
                $task->title = $request['title'];
                $task->description = $request['description'];
                $task->image = $request['image'];
                $task->estimate = $request['estimate'];
                $task->user_id = $request['user_id'];
                $task->role_id = $request['role_id'];
                $task->column_id = $request['column_id'];
                $task->save();

                return response()->json([
                    'success'   => true,
                    'message'   => 'Task updated successfully.',
                    'task'    => $task,
                ], 200);
            }
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Task not found.'
            ], 404);
        }
    }

    public function delete($id)
    {

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title'         => 'required|string|max:199',
            'description'   => 'required',
            'column_id'     => 'required|exists:columns,id',
        ]);
    }
}
