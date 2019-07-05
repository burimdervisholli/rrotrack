<?php

namespace App\Http\Controllers;

use Project;
use Sprint;
use Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SprintController extends Controller
{
    public function create(Request $request)
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
            $sprint = Sprint::create([
                'title'         => $request['title'],
                'start_date'    => $request['start_date'],
                'end_date'      => $request['end_date'],
                'project_id'    => $request['project_id'],
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Sprint created successfully.',
                'sprint'    => $sprint,
            ], 200);
        }
    }

    public function edit(Request $request, $id)
    {
        $sprint = Sprint::findOrFail($id);

        if($sprint)
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
                $sprint->title = $request['title'];
                $sprint->start_date = $request['start_date'];
                $sprint->end_date = $request['end_date'];
                $sprint->project_id = $request['project_id'];
                $sprint->save();
    
                return response()->json([
                    'success'   => true,
                    'message'   => 'Sprint updated successfully.',
                    'sprint'    => $sprint,
                ], 200);    
            }
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Sprint not found!',
            ], 404);
        }
    }

    public function delete($id)
    {

    }

    public function show($id)
    {
        $sprint = Sprint::findOrFail($id);

        if($sprint)
        {
            return response()->json([
                'success'   => true,
                'sprint'    => $sprint,
            ], 200);
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Sprint not found!',
            ], 404);
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title'         => 'required|string|max:199',
            'project_id'    => 'required|exists:projects,id',
        ]);
    }
}
