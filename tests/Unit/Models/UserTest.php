<?php

use \App\Models\User;

beforeEach(function () {
    $this->user = new User();
});

test('check if user\'s fillable fields are correct', function () {
    $expectedFillable = ['name', 'email', 'password'];

    expect($this->user->getFillable())->toEqual($expectedFillable);
});

test('check if user\'s hidden fields are correct', function () {
    $expectedHidden = ['password', 'remember_token'];

    expect($this->user->getHidden())->toEqual($expectedHidden);
});

test('check if user\'s cast fields are correct', function () {
    $expectedCasts = ['id' => 'int', 'email_verified_at' => 'datetime'];

    expect($this->user->getCasts())->toEqual($expectedCasts);
});
