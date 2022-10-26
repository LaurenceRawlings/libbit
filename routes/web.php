<?php

use App\Http\Controllers\GitHubController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\PinController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Index');
});

Route::get('/auth/github', [GitHubController::class, 'redirect']);
Route::get('/auth/callback/github', [GitHubController::class, 'callback']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
            return Inertia::render('Dashboard', [
            'projects' => auth()->user()->pinnedProjects,
            'resources' => auth()->user()->pinnedResources,
        ]);
    })->name('dashboard');

    Route::patch('/pin/project', [PinController::class, 'pinProject'])->name('pin.project');
    Route::patch('/pin/resource', [PinController::class, 'pinResource'])->name('pin.resource');

    Route::resource('projects', ProjectController::class);
    Route::resource('projects.resources', ResourceController::class)->except([
        'index',
    ])->scoped();
});
