<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface {
    /**
     * @param array $data
     * @return Task
     */
    public function create(array $data): Model;

    /**
     * @param string $userId
     * @return mixed
     */
    public function findByUserId(string $userId): Collection;

    /**
     * @param string $id
     * @param string $userId
     * @return Model|null
     */
    public function findByIdAndUserId(string $id, string $userId): ?Model;

    /**
     * @param Model $task
     * @param array $data
     * @return Model
     */
    public function update(Model $task, array $data): Model;

    /**
     * @param Model $task
     * @return bool|null
     */
    public function delete(Model $task): ?bool;
}
