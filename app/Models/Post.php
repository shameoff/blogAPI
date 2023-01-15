<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Post extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'post';
    protected $keyType='string';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'content',
        'readingTime',
        'photoPath'
    ];

}
