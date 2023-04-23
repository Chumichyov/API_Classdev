<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\IndexRequest;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Course;
use App\Models\File;
use App\Models\Task;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(IndexRequest $request, Course $course)
    {
        $credentials = $request->validated();

        //Search
        if ($credentials['search'] != "") {
            $tasks = Task::where('course_id', $course->id)->where('title', 'LIKE', "%{$credentials['search']}%")->orderBy('created_at', 'desc')->get();
            $tasks->loadMissing([
                'type',
                'folders' => function (Builder $query) {
                    $query->where('folder_id', null);
                },
            ]);

            return response([
                "data" => [
                    'tasks' => TaskResource::collection($tasks),
                ]
            ]);
        }

        //Dates sort
        if ($credentials['type'] == "Date") {

            $tasks = Task::where('course_id', $course->id)->orderBy('created_at', 'desc')->get();
            $tasks->loadMissing([
                'type',
                'folders' => function (Builder $query) {
                    $query->where('folder_id', null);
                },
            ]);

            $dates = [];

            foreach ($tasks as $task) {
                if (!in_array($task->created_at->format('d-m-Y'), $dates)) {
                    // $tasks[$task->created_at->format('d-m-Y')] = Task::where('created_at', 'LIKE', "%{$task->created_at->format('Y-m-d')}%")->get(['id', 'title', 'description', 'created_at']);
                    $dates[] = $task->created_at->format('d-m-Y');
                }
            }

            return response([
                "data" => [
                    'tasks' => TaskResource::collection($tasks),
                    'dates' => $dates
                ]
            ]);
        } else {
            $tasks = $course->tasks;
            $tasks->loadMissing([
                'type',
                'folders' => function (Builder $query) {
                    $query->where('folder_id', null);
                },
            ]);
            return TaskResource::collection($tasks);
        }
    }

    public function store(StoreRequest $request, Course $course)
    {
        $credentials = $request->validated();

        $task = Task::create([
            'title' => $credentials['title'],
            'description' => isset($credentials['description']) ? $credentials['description'] : '',
            'course_id' => $course->id,
            'type_id' => $credentials['type']
        ]);

        foreach ($course->members->where("id", "!=", $course->leader_id) as $member) {
            $task->notifications()->create([
                'type_id' => 3,
                'user_id' => null,
                'recipient_id' => $member->id,
                'decision_id' => null,
                'course_id' => $course->id,
                'message' =>  $credentials['type'] == 1 ? "В курсе '{$course->title}' выложено новое задание: '{$task->title}'" : "В курсе '{$course->title}' выложен новый материал: '{$task->title}'",
            ]);
        };

        $task->loadMissing([
            'type',
            'folders' => function (Builder $query) {
                $query->where('folder_id', null);
            },
        ]);

        return new TaskResource($task);
    }

    public function show(Course $course, Task $task)
    {

        $task->loadMissing([
            'files' => function (Builder $query) {
                $query->where('folder_id', null);
            },
            'folders' => function (Builder $query) {
                $query->where('folder_id', null);
            },
            'type',
        ]);

        return new TaskResource($task);
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
