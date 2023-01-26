<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(['error' => "Oh, no! It's empty! Would you like to try again with longer path?"], 404);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'fullName' => 'required|string',
            'birthDate' => 'nullable|date',
            'gender' => 'nullable|in:Female,Male',
            'phoneNumber' => 'nullable|string|numeric',
            'email' => 'required|email:rfc,dns|unique:user,email',
            'password' => 'required|min:8',
        ]);
        $data['password'] = bcrypt($data['password']);
        /**
         * @var User $user
         */
        User::create($data);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Something wrong in MY program. It doesn\'t depend on you! Please, contact me! eshamov0303016@gmail.com', $data], 500);
        }

        $user = $request->user();
        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['token' => $token]);

    }

    /**
     * Returns token if authentication successful else 401
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (!Auth::attempt($data)) {
            return response()->json(['error' => 'Wrong email or password!', $data], 401);
        }

        /** @var User $user */
        $user = $request->user();
        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['token' => $token]);
    }


    /**
     * Returns message if logged out successful
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::guard('sanctum')->logout();
        return response()->json(['message' => 'Successfully logged out', 'status' => 200], 200);
    }


    /**
     * Returns info about authorized user
     * @return JsonResponse
     */
    public function showProfile(): JsonResponse
    {
        $id = Auth::user()->getAuthIdentifier();
        $columns = User::where('id', '=', $id)->get();
        return response()->json(...$columns);

    }

    public function editProfile(Request $request): JsonResponse
    {
        $id = Auth::user()->getAuthIdentifier();
        $data = $request->validate([
            'fullName' => 'string',
            'birthDate' => 'date',
            'gender' => 'in:Female,Male',
            'phoneNumber' => 'string|numeric',
            'email' => 'email:rfc,dns|unique:user,email',
        ]);

        User::where('id', $id)->update([$data]);
        return response()->json($data);
    }

    /**
     * Returns an array of users who have at least 1 post
     * @return JsonResponse
     */
    public function showAuthors()
    {
        $authors = DB::select('SELECT fullName, gender, birthDate, COUNT(posts_table.post_id) as posts, COUNT(likesPerPost) as likes, created_at as created
                FROM user
                         JOIN (SELECT author_id, id AS post_id
                               FROM post) as posts_table
                              on author_id = user.id
                         LEFT JOIN (SELECT post_id, COUNT(*) as likesPerPost
                                    FROM `like`
                                    GROUP BY post_id) as likes_count
                                   on likes_count.post_id = posts_table.post_id
                GROUP BY fullName, gender, birthDate, created_at');
    return response()->json($authors);
    }

}
