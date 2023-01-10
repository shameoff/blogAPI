<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json("Oh, no! It's empty! Would you like to try again with longer path?", 404);
    }

    /**
     *
     * @param Request $request
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'fullName' => 'required|string',
            'birthDate' => 'nullable|date',
            'gender' => 'nullable|in:Female,Male',
            'phoneNumber' => 'nullable|string|numeric',
            'email' => 'required|email:rfc,dns|unique:user,email',
            'password' => 'required|min:8',
        ]);
//        var_dump($data['password']);
//        $data['password'] = bcrypt($data['password']);
//        var_dump($data['password']);
        $user = User::create($data);

        var_dump($user);
        auth()->login($user);

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
            return response()->json(['error' => 'Wrong email or password!'], 401);
        }

        /** @var User $user */
        $user = $request->user();
        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['token'=> $token]);
    }

    public function showProfile(): JsonResponse {
        $id = Auth::user()->getAuthIdentifier();
        $columns = (new \App\Models\User)->select(['*'])->where('id', '=', $id)->get(); //Just dynamic call of select in Eloquent
        return response()->json(...$columns);
    }
}
