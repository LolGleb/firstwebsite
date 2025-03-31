<?php

it('returns a successful response', function () {
    $response = $this->get('http://127.0.0.1:8000/words');

    $response->assertStatus(200);
});

it('returns a successful response2', function () {
    $response = $this->get('http://127.0.0.1:8000/words');

    $response->assertStatus(200);
});

arch()->preset()->laravel();
