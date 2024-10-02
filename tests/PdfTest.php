<?php

use function Pest\Laravel\get;

it('can test', function () {
    expect(true)->toBeTrue();
});

it('can inline a pdf', function () {
    $response = get('pdf');

    $response
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');

    expect($response)->toMatchSnapshot();
});
