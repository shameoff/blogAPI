<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/", function () {
    return "API DOCUMENTATION";
});
Route::get("/author/list", "");

Route::get("/comment/{id}/tree", "");
Route::post("/post/{id}/comment", "");
Route::put("/api/comment/{id}", "");
Route::delete("/comment/{id}", "");

Route::get("/post", "");
Route::get("/post/{id}", "");
Route::post("/post/{postId}/like", "");
Route::delete("/post/{postId}/like", "");

Route::get("/tag", "");

Route::post("/account/register", "");
Route::post("/account/login", "");
Route::post("/account/logout", "");
Route::get("/account/profile", "");
Route::put("/account/profile", "");
