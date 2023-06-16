<?php

use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Unit\Factories\UserFactoryTrait;
use Tests\Unit\InMemoryRepositories\UserRepositoryInMemory;

uses(TestCase::class)->
    beforeEach(function () {
        $this->userRepository = new UserRepositoryInMemory();
        $this->userService = new UserService($this->userRepository);
    });

it('should be able to create a user', function ($user) {
    $createdUser = $this->userService->createUser($user->toArray());

    expect($createdUser->toArray())->toMatchArray($user->except(['password']))
        ->and(Hash::check($user['password'], $createdUser->password))->toBeTrue();
})->with(['user' => UserFactoryTrait::make()]);

