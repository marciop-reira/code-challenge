<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TaskRepository implements TaskRepositoryInterface {
    /**
     * @param Task $task
     */
    public function __construct(private Task $task)
    {}

    /**
     * @param array $data
     * @return Task
     */
    public function create(array $data): Model
    {
        return $this->task->create($data);
    }

    /**
     * @param string $userId
     * @return Collection
     */
    public function findByUserId(string $userId): Collection
    {
        return $this->task
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * @param string $id
     * @param string $userId
     * @return Model|null
     */
    public function findByIdAndUserId(string $id, string $userId): ?Model
    {
        return $this->task
            ->where('user_id', $userId)
            ->find($id);
    }

    /**
     * @param Model $task
     * @param array $data
     * @return Model
     */
    public function update(Model $task, array $data): Model
    {
        $task->update($data);

        return $task;
    }

    /**
     * @param Model $task
     * @return bool|null
     */
    public function delete(Model $task): ?bool
    {
        return $task->delete();
    }
}
