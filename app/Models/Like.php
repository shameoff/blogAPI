<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin Builder
 */
class Like extends Model
{
    protected $table = 'like';
    use HasFactory;


    protected $fillable = [
        'user_id',
        'post_id',
    ];
}
