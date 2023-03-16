<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Course;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Course $course)
    {
        return TaskResource::collection($course->tasks);
    }

    public function store(StoreRequest $request, Course $course)
    {
        $credentials = $request->validated();

        $task = Task::create([
            'title' => $credentials['title'],
            'description' => isset($credentials['title']) ? $credentials['title'] : '',
            'course_id' => $course->id
        ]);

        return new TaskResource($task);
    }

    public function show(Course $course, Task $task)
    {
        return new TaskResource($task->loadMissing('files'));
    }

    public function update(UpdateRequest $request, Course $course, Task $task)
    {
        $credentials = $request->validated();

        $task->update($credentials);

        return new TaskResource($task);
    }

    public function destroy(Course $course, Task $task)
    {
        $task->delete();

        return response([]);
    }
}
