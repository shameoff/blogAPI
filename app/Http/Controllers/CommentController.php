<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Show the form for creating a new resource.
     * @param string $postId
     * @param Request $request
     * @return JsonResponse
     */
    public function create(string $postId, Request $request): JsonResponse
    {
        $userId = Auth::user()['id'];
        $data = $request->validate([
            'postId' => "uuid",
            'parentId' => "uuid",
            'content' => "required|min:3|max:1000"
        ]);
        $content = $data['content'];
        $parentId = $data['parentId'] ?? null;
        if (Post::where('id', '=', $postId)->get()->isEmpty()) {
            return response()->json(['message' => 'Invalid post id'], 400);
        }
        if ($parentId != null and Comment::where('id', '=', $parentId)->get()->isEmpty()) {
            return response()->json(['message' => 'Invalid parent comment id'], 400);
        }

        $result = Comment::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'parent_id' => $parentId,
            'content' => $content]);
        if ($result)
            return response()->json(['message' => "Comment was created successfully"]);
        else return response()->json(['message' => "Something went wrong. Try again later"], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $comment = Comment::where('id', '=', $id)->first();
        if (!isset($comment)) {
            return response()->json(['message' => "Comment with id = $id not found"], 404);
        }
        $authorName = User::where('id', '=', $comment->user_id)->first('fullName')->fullName;
        $subCommentsArray = Comment::where('parent_id', '=', $comment->id)->get();
        return response()->json([
            'id' => $comment->id,
            'createTime' => $comment->created_at,
            'content' => $comment->content,
            'modifiedDate' => $comment->updated_at,
            'deletedDate' => "THIS FIELD IS NOT CREATED YET",
            'authorId' => $comment->authorId,
            'author' => $authorName,
            'subComments' => $subCommentsArray
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(string $id, Request $request): JsonResponse
    {
        $data = $request->validate([
            'content' => "required|min:3|max:1000"
        ]);
        $result = Comment::where('id', '=', $id)->update([$data]);
        if ($result) {
            return response()->json(['message' => 'Comment was updated successfully']);
        } else {
            return response()->json(['message' => 'Something wrong'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        $result = Comment::where('id', '=', $id)->delete();
        if ($result) {
            return response()->json(['message' => 'Comment was deleted successfully']);
        } else {
            return response()->json(['message' => 'Something wrong'], 500);
        }
    }

}
