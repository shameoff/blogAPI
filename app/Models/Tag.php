<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin Builder
 */
class Tag extends Model
{
    use HasFactory, HasUuids;

    protected $keyType='string';
    protected $table = 'tag';
    public $timestamps = true;
    protected $hidden = ['pivot'];

    protected $fillable = ["name"];

    public function posts(){
        return $this->belongsToMany('Post', 'post-tag');
    }
}
