<?php

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
//Only use in production
//Route::get('/products/metadata', [ProductController::class,'metadata']);
//
//Route::post('/products/prices/findOrCreate', [ProductController::class,'findOrCreate']);
//
//Route::get('/products', [ProductController::class,'index']);
//
//Route::post('/donate/thankYou', [ProductController::class,'thankYou']);
//
//Route::post('/donate/session', [ProductController::class,'session']);
//
//Route::get('rapports', [RapportsController::class, 'show']);
//
//Route::post('rapports', [RapportsController::class, 'upload']);
//
//Route::post('contact', [ContactController::class, 'contact']);
//
//Route::post('scholarship', [ScholarshipController::class, 'create']);

Route::get('/', function () {
    return response('',201);
});
