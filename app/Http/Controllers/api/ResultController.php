<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Repositories\ResultRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index()
    {
        try {
            $results = resolve(ResultRepository::class)->all();
            return response()->json([
                $results
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
            'content' => ['required', 'min:3'],
            'task_id' => ['required']
        ]);

        try {
            $result = resolve(ResultRepository::class)->store($request->only(['content', 'task_id']));
            return response()->json([
                'message' => 'result store is successfully.',
                'data' => $result
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Result $result)
    {
        $request->validate([
            'content' => ['required', 'min:3'],
            'task_id' => ['required']
        ]);

        $result = resolve(ResultRepository::class)->update($request->only(['content', 'task_id']), $result);

        try {
            return response()->json([
                'message' => 'result update is completed.',
                'date' => $result
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function destroy(Result $result)
    {
        try {
            resolve(ResultRepository::class)->destroy($result);
            return response()->json([
                'message' => 'result delete is completed.',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function changeStatus(Result $result)
    {
        try {
            resolve(ResultRepository::class)->changeStatus($result);
            return response()->json([
                'message' => 'result status is changed.',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }
}
