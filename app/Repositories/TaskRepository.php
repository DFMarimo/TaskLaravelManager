<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function all()
    {
        return Task::all();
    }

    public function store($data)
    {
        $task = Task::query()->create([
            'title' => $data['title'],
            'status' => $data['status'],
            'score' => $data['score'],
            'description' => $data['description'],
            'expertise_id' => $data['expertise_id'],
            'expired_at' => $data['expired_at'],
        ]);

        $task->expertise()->increment('task_count');

        return $task;
    }

    public function update(Task $task, $data)
    {
        $taskExpertise = $task->expertise_id;

        $task = $task->update([
            'title' => $data['title'] ?: $task->title,
            'status' => $data['status'] ?: $task->status,
            'score' => $data['score'] ?: $task->score,
            'description' => $data['description'] ?: $task->description,
            'expertise_id' => $data['expertise_id'] ?: $task->expertise_id,
            'expired_at' => $data['expired_at'] ?: $task->expired_at,
        ]);

        if (!($taskExpertise == $data['expertise_id'])) {
            ExpertiseRepository::taskCounter($taskExpertise, true);
            ExpertiseRepository::taskCounter($data['expertise_id']);
        }

        return $task;
    }

    public function destroy(Task $task)
    {
        $task->expertise()->decrement('task_count');
        Task::destroy($task->id);
    }

    static public function resultCounter($taskId, $decrement = false)
    {
        if ($decrement) {
            Task::find($taskId)->decrement('result_count');
            return;
        }
        Task::find($taskId)->increment('result_count');
    }
}
