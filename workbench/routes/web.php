<?php

use Breuer\PDF\Facades\PDF;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('pdf', function () {
    $pdf = PDF::html('<h1>hello world</h1>');

    return response($pdf, 200, [
        'content-type' => 'application/pdf',
    ]);
});
