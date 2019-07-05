<?php

namespace App\Http\Controllers;

use Issue;
use Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IssueController extends Controller
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
            $issue = Issue::create([
                'title'         => $request['title'],
                'sprint_id'     => $request['sprint_id'],
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Issue created successfully.',
                'issue'    => $issue,
            ], 200);
        }
    }

    public function edit(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);

        if($issue)
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
                $issue->title = $request['title'];
                $issue->sprint_id = $request['sprint_id'];
                $issue->save();
    
                return response()->json([
                    'success'   => true,
                    'message'   => 'Issue updated successfully.',
                    'issue'    => $issue,
                ], 200);
            }
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Issue not found.'
            ], 404);
        }
    }

    public function delete($id)
    {

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title'        => 'required|string|max:199',
            'sprint_id'    => 'required|exists:sprints,id',
        ]);
    }
}
