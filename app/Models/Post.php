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

    public function user()
    {
        return $this->belongsTo('User', 'author_id');
    }

    public function likes()
    {
        return $this->hasMany('Likes', 'post_id');
    }

    public function comments(){
        return $this->hasMany('Comments', 'post_id');
    }

    public function tags(){
        return $this->belongsToMany('Tag', 'post-tag');
    }

}
