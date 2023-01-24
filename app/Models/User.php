<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable, HasApiTokens;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $incrementing=false;
    protected $keyType='string';

    protected $table = 'user';
    public $timestamps = false;
    protected $fillable = [
        'fullName',
        'birthDate',
        'gender',
        'phoneNumber',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
//        'email_verified_at' => 'datetime',
    ];

    // Rest omitted for brevity
    public function posts()
    {
        return $this->hasMany('Post', 'author_id');
    }
}
