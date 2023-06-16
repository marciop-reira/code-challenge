<?php

namespace App\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskService {
    /**
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(private TaskRepositoryInterface $taskRepository)
    {}

    /**
     * @param array $data
     * @return Model
     */
    public function createTask(array $data): Model
    {
        return $this->taskRepository->create($data);
    }

    /**
     * @param string $userId
     * @return Collection
     */
    public function getAllUserTasks(string $userId): Collection
    {
        return $this->taskRepository->findByUserId($userId);
    }

    /**
     * @param string $id
     * @param string $userId
     * @return Model|null
     */
    public function getOneByIdAndUserId(string $id, string $userId): ?Model
    {
        return $this->getTask($id, $userId);
    }

    /**
     * @param string $id
     * @param string $userId
     * @param array $data
     * @return Model
     */
    public function updateTask(string $id, string $userId, array $data): Model
    {
        $task = $this->getTask($id, $userId);

        if ($data['is_completed']) {
            if (!$task->is_completed) {
                $data['completed_at'] = now();
            }
        } else {
            $data['completed_at'] = null;
        }

        return $this->taskRepository->update($task, $data);
    }

    /**
     * @param string $id
     * @param string $userId
     * @return bool
     */
    public function destroyTask(string $id, string $userId): bool
    {
        $task = $this->getTask($id, $userId);

        return $this->taskRepository->delete($task);
    }

    /**
     * @param string $id
     * @param string $userId
     * @return Model|void|null
     */
    private function getTask(string $id, string $userId)
    {
        $task = $this->taskRepository->findByIdAndUserId($id, $userId);

        if (!$task) {
            throw new NotFoundHttpException('Resource not found.');
        }

        return $task;
    }
}
