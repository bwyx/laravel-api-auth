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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'name' => $request->user()->name,
        'email' => $request->user()->email,
        'isVerified' => !!$request->user()->email_verified_at
    ]);
});

Route::middleware('auth:sanctum')->get('/books', [BookController::class, 'index']);
