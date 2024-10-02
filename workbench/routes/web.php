<?php

use Breuer\PDF\Facades\PDF;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pdf', function () {
    return PDF::view('test')->response();
});

Route::get('/pdf-download', function () {
    return PDF::view('test')->download();
});
