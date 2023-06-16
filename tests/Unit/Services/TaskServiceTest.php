<?php

use App\Services\TaskService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;
use Tests\Unit\Factories\TaskFactoryTrait;
use Tests\Unit\InMemoryRepositories\TaskRepositoryInMemory;

uses(TestCase::class)
    ->beforeEach(function () {
        $this->taskRepository = new TaskRepositoryInMemory();
        $this->taskService = new TaskService($this->taskRepository);

        $this->userId = fake()->uuid;
        $this->task = TaskFactoryTrait::make(userId: $this->userId);
    });

it('should be able to create a task', function ($task) {
    $createdTask = $this->taskService->createTask($task->toArray());

    expect($createdTask->toArray())->toMatchArray($task);
})->with(['task' => TaskFactoryTrait::make()]);

it('should be able to list all user\'s tasks', function () {
    $tasks = TaskFactoryTrait::makeMany(3, ['userId' => $this->userId]);
    $tasks->push(TaskFactoryTrait::make());
    $tasks->each(fn ($task) => $this->taskRepository->create($task->toArray()));

    $result = $this->taskService->getAllUserTasks($this->userId);

    expect($result)->toHaveCount(3)
        ->and($result)->each->toHaveKey('id')
        ->and($result)->each(fn ($item) => $item->user_id->toEqual($this->userId));
});

it('should be able to get a task', function () {
    $createdTask = $this->taskRepository->create($this->task->toArray());

    $result = $this->taskService->getOneByIdAndUserId($createdTask->id, $this->userId);

    expect($result->id)->toEqual($createdTask->id)
        ->and($result->user_id)->toEqual($this->userId)
        ->and($result)->toMatchArray($this->task);
});

it('should not be able to get a non-existing task', function () {
    expect(
        fn () => $this->taskService->getOneByIdAndUserId('non-existing-id', $this->userId)
    )->toThrow(NotFoundHttpException::class);
});

it('should be able to update a task', function () {
    $createdTask = $this->taskRepository->create($this->task->toArray());
    $newData = [
        'title' => 'Updated title',
        'description' => 'Updated description',
        'is_completed' => true
    ];

    $result = $this->taskService->updateTask($createdTask->id, $this->userId, $newData);

    expect($result->toArray())->toMatchArray($newData)
        ->and($result->completed_at)->not->toBeNull();
});

it('should be able to update a task to not completed', function () {
    $createdTask = $this->taskRepository->create($this->task->toArray());
    $this->taskService->updateTask($createdTask->id,  $this->userId, ['is_completed' => true]);

    $result = $this->taskService->updateTask($createdTask->id,  $this->userId, ['is_completed' => false]);

    expect($result->is_completed)->toBeFalse()
        ->and($result->completed_at)->toBeNull();
});

it('should not be able to update a non-existing task', function () {
    expect(
        fn () => $this->taskService->updateTask(
            'non-existing-id',
            $this->userId,
            ['is_completed' => true]
        )
    )->toThrow(NotFoundHttpException::class);
});

it('should be able to delete a task', function () {
    $createdTask = $this->taskRepository->create($this->task->toArray());

    $result = $this->taskService->destroyTask($createdTask->id, $this->userId);

    expect($result)->toBeTrue();
});

it('should not be able to delete a non-existing task', function () {
    expect(
        fn () => $this->taskService->destroyTask('non-existing-id', $this->userId)
    )->toThrow(NotFoundHttpException::class);
});

