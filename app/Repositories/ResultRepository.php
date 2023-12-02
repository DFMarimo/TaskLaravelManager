<?php

namespace App\Repositories;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultRepository
{
    public function all()
    {
        return Result::all();
    }

    public function store($data)
    {
        $result = Auth::user()->results()->create([
            'content' => $data['content'],
            'task_id' => $data['task_id'],
            'status' => false
        ]);
        TaskRepository::resultCounter($data['task_id']);
        return $result;
    }

    public function update($data, Result $result)
    {
        return Auth::user()->results()->find($result->id)->update([
            'content' => $data['content'],
            'task_id' => $data['task_id']
        ]);
    }

    public function destroy(Result $result)
    {
        TaskRepository::resultCounter($result->task_id, true);
        Result::destroy($result->id);
    }

    public function changeStatus(Result $result)
    {
        if ($result->status) {
            $result->update(['status' => false]);
            UserRepository::minScore($result->user_id, $result->task()->score);
            return;
        }

        $result->update(['status' => true]);
        UserRepository::addScore($result->user_id, $result->task()->score);
    }
}
