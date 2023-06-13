<?php

use App\Repositories\InMemory\UserRepositoryInMemory;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class)->
    beforeEach(function () {
        $this->userRepository = new UserRepositoryInMemory();
        $this->userService = new UserService($this->userRepository);

        $this->user = [
            'name' => fake('pt_BR')->name,
            'email' => fake('pt_BR')->email,
            'password' => fake('pt_BR')->password,
        ];
    });

it('should be able to create a user', function () {
    $createdUser = $this->userService->createUser($this->user);

    expect($this->user)->toMatchArray($createdUser->toArray())
        ->and(Hash::check($this->user['password'], $createdUser->password))->toBeTrue();
});

