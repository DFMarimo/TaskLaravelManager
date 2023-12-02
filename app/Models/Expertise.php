<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    use HasFactory;

    protected $table = 'expertises';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'alt',
        'task_count',
        'user_count',
        'parent_id',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'expertise_user',
            'expertise_id',
            'user_id'
        );
    }

    public function task()
    {
        return $this->hasOne(
            Task::class,
            'expertise_id',
            'id'
        );
    }
}
