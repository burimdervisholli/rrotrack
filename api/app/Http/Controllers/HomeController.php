<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use App\Sprint;
use App\Issue;
use App\Column;
use App\Task;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homeStatistics()
    {
        $users = User::all();
        $projects = Project::all();
        $sprints = Sprint::all();
        $issues = Issue::all();
        $tasks = Task::all();

        return response()->json([
            'success'   => true,
            'users'     => count($users),
            'projects'  => count($projects),
            'sprints'   => count($sprints),
            'issues'    => count($issues),
            'tasks'     => count($tasks),
        ], 200);
    }
}
