<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'status',
        'score',
        'result_count',
        'expertise_id',
        'description',
        'expired_at'
    ];

    public function expertise()
    {
        return $this->belongsTo(Expertise::class, 'expertise_id', 'id');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'task_id', 'id');
    }

}
