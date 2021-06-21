<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RapportsController;
use App\Http\Controllers\ScholarshipController;
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

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class,'index']);
    Route::get('/metadata', [ProductController::class,'metadata']);
    Route::post('/prices/findOrCreate', [ProductController::class,'findOrCreate']);
});

Route::group(['prefix' => 'donate'], function () {
    Route::post('/thankYou', [ProductController::class,'thankYou']);
    Route::post('/session', [ProductController::class,'session']);
});

Route::group(['prefix' => 'rapports'], function () {
    Route::get('/', [RapportsController::class, 'show']);
    Route::get('/download/{id}', [RapportsController::class, 'download']);
    Route::get('/types', [RapportsController::class, 'types']);

    Route::post('/', [RapportsController::class, 'upload']);

    Route::delete('/{id}', [RapportsController::class, 'delete']);
});

Route::group(['prefix' => 'paypal/plans'], function () {
    Route::post('/', [ProductController::class,'create_paypal_plan']);
    Route::get('/{name}', [ProductController::class,'paypal_plans']);

});

Route::post('contact', [ContactController::class, 'contact']);
Route::post('scholarship', [ScholarshipController::class, 'create']);

