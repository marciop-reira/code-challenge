<?php

namespace Tests\Unit\Factories;

use Illuminate\Support\Collection;

trait TaskFactoryTrait {
    public static function make(string $userId = null, string $title = null, string $description = null): Collection
    {
        return collect([
            'user_id' => $userId ?? fake()->uuid,
            'title' => $title ?? fake('pt_BR')->text(20),
            'description' => $description ?? fake('pt_BR')->text(),
            'is_completed' => false
        ]);
    }

    public static function makeMany(int $amount, $parameters): Collection
    {
        $tasks = collect();

        for ($i = 0; $i < $amount; $i++) {
            $tasks->push(call_user_func_array(__NAMESPACE__ . '\TaskFactoryTrait::make', $parameters));
        }

        return $tasks;
    }
}
