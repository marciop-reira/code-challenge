<?php

namespace App\Repositories\InMemory;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepositoryInMemory implements UserRepositoryInterface {
    /**
     * @param array $users
     */
    public function __construct(private array $users = [])
    {}

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $user = new User($data);

        $this->users[] = $user;

        return $user;
    }
}
