<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

//use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $table = 'users';

    /*public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($obj) {
            $obj->id = Uuid::uuid4()->toString();
        });
    }*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'score',
        'is_block',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeIsSuperAdmin($query, User $user)
    {
        return !!($user->role = 'super-admin');
    }

    public function scopeIsAdmin($query, User $user)
    {
        return !!($user->role = 'admin');
    }

    /*
     * Relations user with other
     */

    public function expertises()
    {
        return $this->belongsToMany(
            Expertise::class,
            'expertise_user',
            'user_id',
            'expertise_id',
        );
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /*
     * Mutators and Accessors
     */
    public function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::make($value),
        );
    }
}
