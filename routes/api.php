<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

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

Route::get('/user', function (Request $request) {
    $user = $request->user();
    
    if(!$user) {
        return response()->json([
            'isAuthenticated' => false
        ]);
    }

    return response()->json([
        'isAuthenticated' => true,
        'name' => $user->name,
        'email' => $user->email,
        'isVerified' => $user->isVerified(),
        'avatar' => $user->providers()->latest()->first()->avatar ?? null
    ]);
});

Route::middleware('auth:sanctum')->get('/books', [BookController::class, 'index']);
