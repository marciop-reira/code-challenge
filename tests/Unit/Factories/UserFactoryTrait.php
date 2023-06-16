<?php

namespace Tests\Unit\Factories;

use Illuminate\Support\Collection;

trait UserFactoryTrait {
    public static function make(string $name = null, string $email = null, string $password = null): Collection
    {
        return collect([
            'name' => $name ?? fake('pt_BR')->name,
            'email' => $email ?? fake('pt_BR')->email,
            'password' => $password ?? fake('pt_BR')->password,
        ]);
    }
}
