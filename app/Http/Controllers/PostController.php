<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Li;
use function PHPUnit\Framework\isEmpty;
use function Symfony\Component\VarDumper\Dumper\esc;

class PostController extends Controller
{

//    public function create() {}

    public function get(Request $request)
    {

        $posts = Post::filter($request->all())->get(['*']);

        $myId = Auth::guard('sanctum')->user()->id ?? null;

        $postIdsLikedByMe = $myId != null ?
            array_map(function ($elem){
                return $elem['post_id'];
            }, Like::where('user_id', '=', $myId)->get('post_id')->toArray()) : [];

        foreach ($posts as $post){
            $post['hasLike'] = in_array($post->id, $postIdsLikedByMe);
            $post['tags'] = $post->tags()->get(['tag.id', 'name', 'tag.created_at']);
        }

        return response()->json($posts);
    }

    /**
     * Not the best but it works!
     * Get detailed info about post
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function getDetailed($id, Request $request)
    {

        $request->user('sanctum');

        $post = Post::where('id', '=', $id)->first();
        $authorName = User::where('id', '=', $post['author_id'])->first('fullName')->fullName;

        $likesAmount = Like::where('post_id', '=', $post->id)->count();
        $hasLike = Auth::guard('sanctum')->user() != null &&
            Like::where('post_id', '=', $post->id, 'and')->
            where('user_id', '=', Auth::guard('sanctum')->
            user()->getAuthIdentifier())->first() != null;

        $commentsAmount = Comment::where('post_id', '=', $post->id)->count();
        $commentsArray = $post->comments()->withTrashed()->where('parent_id', '=', null)->get();
        foreach ($commentsArray as $comment){
            $comment['subComments'] = $comment->children()->count();
        }

        $tagsArray = $post->tags()->get(['tag.id', 'name', 'tag.created_at']);

        return response()->json([
            'id' => $post['id'],
            'createTime' => $post->created_at,
            'title' => $post->title,
            'description' => $post->content,
            'readingTime' => $post->readingTime,
            'image' => $post->photoPath,
            'authorId' => $post->author_id,
            'author' => $authorName,
            'likes' => $likesAmount,
            'hasLike' => $hasLike,
            'commentsCount' => $commentsAmount,
            'tags' => $tagsArray,
            'comments' => $commentsArray
        ]);
    }

    public function getDetailed1($id, Request $request): JsonResponse
    {

        $request->user('sanctum');
        $post = Post::where('id', '=', $id)->first();
        $data = DB::selectOne('SELECT id, title, author_id as authorId, author, content as description, readingTime, photoPath as imgage, created_at as createTime, likes, comments
FROM post
    LEFT JOIN (SELECT post_id, COUNT(*) as likes
          FROM `like`
          GROUP BY post_id) as lct # Likes-count-table
    on lct.post_id = post.id
    LEFT JOIN (SELECT post_id, COUNT(*) as comments
          FROM comment
          GROUP BY post_id) as cct # Comments-count-table
    on cct.post_id = post.id
    LEFT JOIN (SELECT id as user_id, fullName as author
            FROM user) as udt # User-data-table
        on user_id = post.author_id
WHERE id = ?', [$id], false);
        $data = array_map(fn($x) => (array)$x, $data);
        $hasLike = Auth::guard('sanctum')->user() != null &&
            Like::where('post_id', '=', $post->id, 'and')->
            where('user_id', '=', Auth::guard('sanctum')->
            user()->id)->first() != null;
        // Эти две строки надо проверить!
        $data['commentsArray'] = $post->comments();
        $data['tagsArray'] = $post->tags();

        return response()->json(
//            'id' => $post['id'],
//            'createTime' => $post->created_at,
//            'title' => $post->title,
//            'description' => $post->content,
//            'readingTime' => $post->readingTime,
//            'image' => $post->photoPath,
//            'authorId' => $post->author_id,
//            'author' => $authorName,
//            'likes' => $likesAmount,
//            'hasLike' => $hasLike,
//            'commentsCount' => $commentsAmount,
//            'tags' => $tagsArray,
//            'comments' => $commentsArray
        $data);
    }

    public function like($postId)
    {
        $userId = Auth::user()['id'];
        $result = Like::create(['user_id' => $userId, 'post_id' => $postId]);
        return response()->json(['message' => "Post was liked successfully!"]);
    }


    public function unlike($postId)
    {
        $userId = Auth::user()['id'];

        $result = Like::where('user_id', '=', $userId, 'and')->where('post_id', '=', $postId)->delete();
        if ($result == 1) {
            return response()->json(['message' => 'Post unliked successfully.']);
        } else if (Post::where('id', '=', $postId)->get()->isEmpty()) {
            return response()->json(['message' => "Post with ID = $postId doesn't exist."], 404);
        } else {
            return response()->json(['message' => "Post already unliked."], 400);
        }
    }

    public function showTags()
    {
        return response()->json(Tag::all(['id', 'name', 'created_at']));
    }
}
