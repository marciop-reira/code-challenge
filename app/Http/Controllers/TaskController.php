<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskController extends Controller
{
    /**
     * @param TaskService $taskService
     */
    public function __construct(private TaskService $taskService)
    {}

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return $this->taskService->getAllUserTasks(request()->user()->id);
    }

    /**
     * @param StoreTaskRequest $request
     * @return Response
     */
    public function store(StoreTaskRequest $request): Response
    {
        $createdTask = $this->taskService->createTask([
            'user_id' => $request->user()->id,
            ...$request->only(['title', 'description'])
        ]);

        if ($request->hasFile('files')) {
            $this->uploadTaskFiles();
        }

        return response($createdTask, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @return Model
     */
    public function show(string $id): Model | JsonResponse
    {
        try {
            return $this->taskService->getOneByIdAndUserId($id, request()->user()->id);
        } catch (HttpException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getStatusCode());
        }
    }

    /**
     * @param UpdateTaskRequest $request
     * @param string $id
     * @return Model|JsonResponse
     */
    public function update(UpdateTaskRequest $request, string $id): Model | JsonResponse
    {
        try {
            return $this->taskService->updateTask(
                $id,
                $request->user()->id,
                $request->only(['title', 'description', 'is_completed'])
            );
        } catch (HttpException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getStatusCode());
        }
    }

    /**
     * @param string $id
     * @return Response
     */
    public function destroy(string $id): Response
    {
        try {
            if ($this->taskService->destroyTask($id, request()->user()->id)) {
                return response(['message' => 'Resource has been deleted.'], Response::HTTP_OK);
            }
        } catch (HttpException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getStatusCode());
        }
    }

    /**
     * @return array
     */
    private function uploadTaskFiles(): array
    {
        $uploadedFiles = [];

        /** @var UploadedFile $file */
        foreach (request()->allFiles()['files'] as $file) {
            $uploadedFiles[] = [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'path' => $file->store(env('ATTACHMENT_DIR'))
            ];
        }

        return $uploadedFiles;
    }
}
