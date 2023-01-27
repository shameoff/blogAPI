<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Post extends Model
{

    use HasFactory, HasUuids, Filterable;

    protected $table = 'post';
    protected $keyType='string';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'content',
        'readingTime',
        'photoPath'
    ];

    protected $hidden = ['pivot'];
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like', 'post_id');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment', 'post_id');
    }

    public function tags(){
        return $this->belongsToMany('App\Models\Tag', 'post-tag');
    }

}
