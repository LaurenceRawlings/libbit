<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
use App\Models\Tag;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::Resource('projects', ProjectController::class, ['as' => 'api'])->except(['edit', 'create']);
    Route::Resource('projects.resources', ResourceController::class, ['as' => 'api'])->except(['edit', 'create']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('/tags/{query}', function ($query) {
    return Tag::where('name', 'like', '%'.$query.'%')->get();
});

Route::get('/tags', function () {
    return Tag::all();
});
