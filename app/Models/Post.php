<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'post';
//    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'readingTime',
        'photoPath'
    ];
    public $timestamps = true;
}
