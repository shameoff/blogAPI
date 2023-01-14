<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;

class PostController extends Controller
{
    public function index(){
        return "index of Post Controller";
    }
    public function create(){

    }
    public function get(){
        $columns = Post::where('id', '=', '*')->get();
        return response()->json(...$columns);
    }
    public function getDetailed($id) {
        $columns = Post::where('id', '=', $id)->get();
        return response()->json(...$columns);
    }

    public function like($postId){
        $userId = Auth::user()['id'];
        $result = Like::create(['user_id' => $userId, 'post_id' =>  $postId]);
        return response()->json(['message' => "Post was liked successfully!"]);
    }


    public function unlike($postId){
        $userId = Auth::user()['id'];

        $result = Like::where('user_id', '=', $userId, 'and')->where('post_id', '=', $postId)->delete();
        if ($result == 1) {
            return response()->json(['message'=>'Post unliked successfully.']);
        }
        else if (Post::where('id', '=', $postId)->get()->isEmpty()) {
            return response()->json(['message' => "Post with ID = $postId doesn't exist."], 404);
        }
        else{
            return response()->json(['message' => "Post already unliked."], 400);
        }
    }

    public function showTags(){
        return response()->json(Tag::all(['id', 'name', 'created_at']));
    }
}
