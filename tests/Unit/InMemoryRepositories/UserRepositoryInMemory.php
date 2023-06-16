<?php

namespace Tests\Unit\InMemoryRepositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepositoryInMemory implements UserRepositoryInterface {

    /**
     * @var Collection
     */
    private Collection $users;

    /**
     *
     */
    public function __construct()
    {
        $this->users = collect();
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $user = new User($data);

        $this->users->push($user);

        return $user;
    }
}
