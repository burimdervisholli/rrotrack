<?php

namespace App\Http\Controllers;

use App\Project;
use App\Sprint;
use App\Issue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
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
            $project = Project::create([
                'title'         => $request['title'],
                'description'   => $request['description'],
                'deadline'      => $request['deadline']
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Project created successfully.',
                'project'   => $project,
            ], 200);
        }
    }

    public function edit(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        if($project)
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
                $project->title = $request['title'];
                $project->description = $request['description'];
                $project->deadline = $request['deadline'];
                $project->save();
    
                return response()->json([
                    'success'   => true,
                    'message'   => 'Project updated successfully.',
                    'project'   => $project,
                ], 200);
            }
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Project not found!',
            ], 404);
        }
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);
        
        if($project)
        {
            $project->delete();
            return response()->json([
                'success'   => true,
                'message'   => 'Project deleted successfully.',
            ], 200);
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Project not found!',
            ], 404);
        }
    }

    public function list()
    {
        $projects = Project::all();
        $processedProjects = [];
        foreach($projects as $project)
        {
            $startDate = new Carbon($project->created_at);
            $deadline = new Carbon($project->deadline);
            $project = [
                'id'            => $project->id,
                'title'         => $project->title,
                'created_at'    => $startDate->toFormattedDateString(),
                'deadline'      => $deadline->diffForHumans(),
                'users'         => count($project->users),
                'sprints'       => count($project->sprints)
            ];
            array_push($processedProjects, $project);
        }

        return response()->json([
            'success'   => true,
            'projects'  => $processedProjects,
        ], 200);
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        
        //Build the results
        $data = [];
        $data['project'] = $project; 
        $data['project']['sprints'] = $project->sprints;
        foreach($project->sprints as $sprint)
        {
            $sprint->issues;
        }        

        if($project)
        {
            return response()->json([
                'success'   => true,
                'project'   => $data,
            ], 200);
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Project not found!',
            ], 404);
        }        
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|string|max:199',
        ]);
    }
}
