<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin Builder
 */
class Comment extends Model
{
    use HasFactory, HasUuids;

    protected $keyType='string';
    protected $table = 'comment';
    public $timestamps = true;

    protected $fillable = [
        "user_id",
        "post_id",
        "parent_id",
    ];
}
