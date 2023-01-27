<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class PostFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [''];

    /** LEFT JOIN (SELECT post_id, COUNT(*) as likes
    FROM `like`
    GROUP BY post_id) as lct # Likes-count-table
    on lct.post_id = post.id
    LEFT JOIN (SELECT post_id, COUNT(*) as comments
    FROM comment
    GROUP BY post_id) as cct # Comments-count-table
    on cct.post_id = post.id
*/
    public function setup(){
        $this->join('user', 'author_id', '=', 'user.id')
            ->joinSub('select post_id, COUNT(*) as likes FROM `like` GROUP BY post_id',
                'lct', # Likes-count-table
                'lct.post_id', '=', 'post.id', 'left')
            ->joinSub('SELECT post_id, COUNT(*) as comments FROM comment GROUP BY post_id',
                'cct', # Comments-count-table
                'cct.post_id', '=', 'post.id', 'left')
            ->selectRaw('post.id as id,
            post.created_at as createTime,
            title,
            post.content as description,
            readingTime,
            photoPath as image,
            author_id as authorId,
            user.fullName as author,
            likes,
            comments as commentsCount
            ');
    }
    public function author($author)
    {
        return $this->whereHas('author', function ($query) use ($author) {
            return $query->where('fullName', 'LIKE', "%${author}%");
        });
    }

    public function min($min)
    {
        return $this->where('readingTime', '>', $min);
    }

    public function max($max)
    {
        return $this->where('readingTime', '<', $max);
    }

    public function page($pageNum)
    {
        $perPage = 5;
        if (request()->has('size')) {
            $perPage = request()->size;
        }
        $this->paginate($perPage, ['*'], 'page', $pageNum);
    }

    public function size($perPage)
    {
        $pageNum = 1;
        if (request()->has('page')) {
            $pageNum = request()->page;
        }
        $this->paginate($perPage, ['*'], 'page', $pageNum);
    }

    public function tags($tagIds)
    {
        return $this->whereHas('tags', function ($query) use ($tagIds) {
            return $query->whereIn('tag.id', $tagIds);
        });
    }

    public function sorting($sortType)
    {
        switch ($sortType) {
            case "CreateDesc":
                return $this->orderBy('created_at', 'desc');
            case "CreateAsc":
                return $this->orderBy('created_at');
            case "LikeAsc":

            case "LikeDesc":
                return $this->orderByRaw('', 'desc');
        }
    }
}
