<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "INDEX OF ACCOUNT CONTROLLER";
    }

    /**
     * Show the form for creating a new resource.
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
        User::create($data);

        $user = new User([
            'fullName' => $data['fullName'],
            'birthDate' => $data['birthDate'],
            'gender' => $data['gender'],
            'phoneNumber' => $data['phoneNumber'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);
        return \response()->json([
        "message" => 'Registration',
//          $request->validated(),
    ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
