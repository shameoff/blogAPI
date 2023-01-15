<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Li;
use function PHPUnit\Framework\isEmpty;
use function Symfony\Component\VarDumper\Dumper\esc;

class PostController extends Controller
{
    public function index()
    {
        return "index of Post Controller";
    }

    public function create()
    {

    }

    public function get()
    {
        $columns = Post::where('id', '=', '*')->get();
        return response()->json(...$columns);
    }

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
        // Вот эту штуку надо поправить
        $commentsArray = Comment::where('post_id', '=', $post->id)->get();
        // А тут вообще написать еще одну модель и работать с ней
        $tagsArray = [];

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
