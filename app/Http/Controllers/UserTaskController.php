<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(User $user)
    {
        $tasks = DB::table('tasks')
            ->join('expertise_user', 'tasks.expertise_id', '=', 'expertise_user.expertise_id')
            ->where('expertise_user.user_id', $user->id)
            ->select('tasks.*')
            ->get();

        return response()->json($tasks, 200);
    }

    public function show(User $user, Task $task)
    {
        // use $user for results
        $results = $user->results()->where('task_id', $task->id)->get();

        return response()->json($task, 200);
    }
}
