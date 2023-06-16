<?php

namespace Tests\Unit\InMemoryRepositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TaskRepositoryInMemory implements TaskRepositoryInterface {
    /**
     * @var Collection
     */
    private Collection $tasks;

    /**
     *
     */
    public function __construct()
    {
        $this->tasks = collect();
    }


    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        $task = new Task($data, true);

        $this->tasks->push($task);

        return $task;
    }

    /**
     * @param string $userId
     * @return Collection
     */
    public function findByUserId(string $userId): Collection
    {
        return $this->tasks->filter(fn ($task) => $task->user_id === $userId);
    }

    /**
     * @param string $id
     * @param string $userId
     * @return Model|null
     */
    public function findByIdAndUserId(string $id, string $userId): ?Model
    {
        return $this->tasks->first(fn ($task) => $task->id === $id && $task->user_id === $userId);
    }

    /**
     * @param Model $task
     * @param array $data
     * @return Model
     */
    public function update(Model $task, array $data): Model
    {
        $taskIndex = $this->tasks->search(
            fn ($item) => $item->id === $task->id && $item->user_id == $task->user_id
        );

        foreach ($data as $key => $value) {
            $this->tasks[$taskIndex]->{$key} = $value;
        }

        return $this->tasks[$taskIndex];
    }

    /**
     * @param Model $task
     * @return bool|null
     */
    public function delete(Model $task): ?bool
    {
        $initialCount = $this->tasks->count();

        $this->tasks = $this->tasks->filter(fn ($item) => $item->id !== $task->id);

        return $initialCount !== $this->tasks->count();
    }
}
