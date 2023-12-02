<?php

namespace App\Repositories;

use App\Models\Expertise;
use Illuminate\Http\Request;

class ExpertiseRepository
{
    public function all()
    {
        return Expertise::all();
    }

    public function store($data)
    {
        return Expertise::query()->create([
            'name' => $data['name'],
            'alt' => $data['alt'],
            'parent_id' => $data['parent_id']
        ]);
    }

    public function update($data, Expertise $expertise)
    {
        return $expertise->update([
            'name' => $data['name'] ?? $expertise->name,
            'alt' => $data['alt'] ?? $expertise->alt,
            'parent_id' => $data['parent_id'] ?? $expertise->parent_id
        ]);
    }

    public function destroy(Expertise $expertise)
    {
        return Expertise::destroy($expertise);
    }

    static public function taskCounter($expertiseId, $decrement = false)
    {
        if ($decrement) {
            Expertise::find($expertiseId)->decrement('task_count');
            return;
        }
        Expertise::find($expertiseId)->increment('task_count');
    }

    static public function userCounter($expertiseId, $decrement = false)
    {
        if ($decrement) {
            Expertise::where('id', $expertiseId)->decrement('user_count');
            return;
        }
        Expertise::where('id', $expertiseId)->increment('user_count');
    }
}
