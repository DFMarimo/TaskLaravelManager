<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index()
    {
        try {
            $tasks = resolve(TaskRepository::class)->all();
            return response()->json([
                $tasks
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'min:5', 'string'],
            'status' => ['required'],
            'score' => ['required', 'integer'],
            'description' => ['required', 'min:10'],
            'expertise_id' => ['required'],
            'expired_at' => ['required']
        ]);

        try {
            $task = resolve(TaskRepository::class)->store(
                $request->only(['title', 'status', 'score', 'description', 'expertise_id', 'expired_at'])
            );

            return response()->json([
                'message' => 'task store successfully.',
                'data' => $task
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => ['min:5', 'string'],
            'score' => ['integer'],
            'description' => ['min:10'],
        ]);

        try {
            $updateTask = resolve(TaskRepository::class)->update(
                $task,
                $request->only(['title', 'status', 'score', 'description', 'expertise_id', 'expired_at'])
            );

            return response()->json([
                'message' => 'task update successfully.',
                'data' => $updateTask
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $exception->getMessage()
            ], 500);
        }

    }

    public function destroy(Task $task)
    {
        try {
            resolve(TaskRepository::class)->destroy($task);
            return response()->json([
                'message' => 'task delete successfully.',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

}
