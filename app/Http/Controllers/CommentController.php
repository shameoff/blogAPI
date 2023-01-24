<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class CommentController extends Controller
{

    /**
     * Create comment for certain post from request.
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

    private function getChildComments(Comment $comment): array
    {
        $children = $comment->children()->withTrashed()->where('post_id', '=', $comment->post_id)
            ->join('user', 'user_id', '=', 'user.id')
            ->get(['comment.id', 'post_id', 'parent_id', 'comment.created_at', 'content', 'comment.updated_at', 'deleted_at', 'user_id', 'fullName']);
        $fixed_children = [];

        /** @var Comment $child */
        foreach ($children as $child)
            $fixed_children[] = [
                'id' => $child->id,
                'createTime' => $child->created_at,
                'content' => isNull($child->deleted_at) ? $child->content : 'DELETED',
                'modifiedDate' => $child->updated_at,
                'deletedDate' => $child->deleted_at,
                'authorId' => $child->user_id,
                'author' => $child->fullName,
                'subComments' => $this->getChildComments($child)
            ];
        return $fixed_children;
    }

    /**
     * Show all child comments of certain comment in the tree form.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function showTree(string $id): JsonResponse
    {
        /** @var Comment $comment */
        $comment = Comment::withTrashed()->where('comment.id', '=', $id)
            ->join('user', 'user_id', '=', 'user.id')
            ->first(['comment.id', 'post_id', 'parent_id', 'comment.created_at', 'content', 'comment.updated_at', 'deleted_at', 'user_id', 'fullName']);

        if (!isset($comment)) {
            return response()->json(['message' => "Comment with id = $id not found"], 404);
        }

        return response()->json($this->getChildComments($comment));
    }

    /**
     * Edit fields sent in a request in the comment with certain id
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
     * Delete a comment with certain id
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
