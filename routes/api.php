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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get("/", function () {
    return "API DOCUMENTATION";
});

Route::get("/tag", "App\Http\Controllers\PostController@showTags");
Route::get("/author/list", "App\Http\Controllers\AccountController@showAuthors");

Route::prefix("/post")->group(function () {
    Route::get("/", ['uses' => 'App\Http\Controllers\PostController@get']);
    Route::get("/{id}", ['uses' => 'App\Http\Controllers\PostController@getDetailed']);
    Route::post("/{id}/comment", ['middleware' => 'auth:sanctum', 'uses' => 'App\Http\Controllers\PostController@comment']);
    Route::post("/{postId}/like", ['middleware' => 'auth:sanctum', 'uses' => 'App\Http\Controllers\PostController@like']);
    Route::delete("/{postId}/like", ['middleware' => 'auth:sanctum', 'uses' => 'App\Http\Controllers\PostController@unlike']);
});

Route::prefix("/account")->group(function () {
    Route::get("/", "App\Http\Controllers\UserController@index");
    Route::get("/profile", ['middleware' => 'auth:sanctum', 'uses' => "App\Http\Controllers\UserController@showProfile"]);
    Route::put("/profile", ['middleware' => 'auth:sanctum', 'uses' => "App\Http\Controllers\UserController@editProfile"]);
    Route::post("/register", "App\Http\Controllers\UserController@register");
    Route::post("/login", "App\Http\Controllers\UserController@login");
    Route::post("/logout", ['middleware' => 'auth:sanctum', 'uses' => "App\Http\Controllers\UserController@logout"]);
});
Route::prefix("/comment")->group(function (){
    Route::get("/{id}/tree", "App\Http\Controllers\CommentController@showTree");
    Route::put("/{id}", ['middleware' => 'auth:sanctum', 'uses' => "App\Http\Controllers\CommentController@edit"]);
    Route::delete("/{id}", ['middleware' => 'auth:sanctum', 'uses' => "App\Http\Controllers\CommentController@delete"]);
});


