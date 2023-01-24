<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @mixin Builder
 */
class Comment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType='string';
    protected $table = 'comment';
    public $timestamps = true;

    protected $fillable = [
        "user_id",
        "post_id",
        "parent_id",
        "content",
        "deleted_at"
    ];

    public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
