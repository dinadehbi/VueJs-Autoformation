<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function ($locale, Request $request) {
    if (!in_array($locale, ['en', 'fr', 'ar', 'es'])) {
        abort(400);
    }
    session(['locale' => $locale]); 
    return response()->json(['success' => true]);
});
