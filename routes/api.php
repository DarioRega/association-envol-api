<?php

use App\Http\Controllers;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RapportsController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\UserController;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/products/metadata', [ProductController::class,'metadata']);

Route::post('/products/prices/findOrCreate', [ProductController::class,'findOrCreate']);

Route::get('/products', [ProductController::class,'index']);

Route::post('/donate/thankYou', [ProductController::class,'thankYou']);

Route::post('/donate/session', [ProductController::class,'session']);

Route::get('rapports', [RapportsController::class, 'show']);

Route::post('rapports', [RapportsController::class, 'upload']);

Route::post('contact', [ContactController::class, 'contact']);

Route::post('scholarship', [ScholarshipController::class, 'create']);



