<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index']);

Route::get('/', function () {
    return view('index'); 
});

Route::get('/{any}', function () {
    return view('index'); 
})->where('any', '.*');