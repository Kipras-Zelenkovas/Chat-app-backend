<?php

use App\Http\Controllers\Auth\Google;
use App\Http\Controllers\Auth\Ordinary;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/user')->group(function(){

    Route::prefix('/google')->middleware('guest')->group(function(){
        Route::get('/redirect', [Google::class, 'redirect']);
        Route::get('/callback', [Google::class, 'callback']);
    });

    Route::post('/register', [Ordinary::class, 'register'])->middleware('guest');
    Route::post('/login', [Ordinary::class, 'login'])->middleware('guest');

    Route::post('/forgot-password', [Ordinary::class, 'forgot_password']);
    Route::post('/reset-password', [Ordinary::class, 'reset_password']);

    Route::post('/logout', [Ordinary::class, 'logout'])->middleware('auth:sanctum');
});



//for thunder client testing
Route::prefix("thunder")->group(function(){

    Route::get('/token', function(){
        return response()->json(csrf_token());
    });

    Route::get('/users', function(){
        return response()->json(User::all());
    });

});

