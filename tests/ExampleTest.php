<?php

use Breuer\PDF\Facades\PDF;

it('can test', function () {
    expect(true)->toBeTrue();
});

it('can generate a pdf', function () {
    $pdf = PDF::html('<h1>Hello World</h1>');

    expect($pdf)->not->toBeNull();
});
