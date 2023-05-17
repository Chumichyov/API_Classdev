<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\IndexRequest;
use App\Http\Requests\Task\PublishedRequest;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Course;
use App\Models\File;
use App\Models\Folder;
use App\Models\Task;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(IndexRequest $request, Course $course)
    {
        $credentials = $request->validated();

        //Search
        if ($credentials['search'] != "") {
            if (auth()->user()->id == $course->leader_id) {
                $tasks = Task::where('course_id', $course->id)->where('title', 'LIKE', "%{$credentials['search']}%")->orderBy('created_at', 'desc')->get();
            } else {
                $tasks = Task::where('course_id', $course->id)->where('title', 'LIKE', "%{$credentials['search']}%")->where('is_published', 1)->orderBy('created_at', 'desc')->get();
            }

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
        // if ($credentials['type'] == "Date") {
        if (auth()->user()->id == $course->leader_id) {
            $tasks = Task::where('course_id', $course->id)->orderBy('created_at', 'desc')->get();
        } else {
            $tasks = Task::where('course_id', $course->id)->where('is_published', 1)->orderBy('created_at', 'desc')->get();
        }

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
        // } else {
        //     if (auth()->user()->id == $course->leader_id) {
        //         $tasks = Task::where('course_id', $course->id)->orderBy('created_at', 'desc')->get();
        //     } else {
        //         $tasks = Task::where('course_id', $course->id)->where('is_published', 1)->orderBy('created_at', 'desc')->get();
        //     }

        //     $tasks->loadMissing([
        //         'type',
        //         'folders' => function (Builder $query) {
        //             $query->where('folder_id', null);
        //         },
        //     ]);
        //     return TaskResource::collection($tasks);
        // }
    }

    public function published(Course $course, Task $task)
    {
        $task->update([
            'is_published' => $task->is_published == 0 ? 1 : 0
        ]);

        $task->loadMissing([
            'type',
            'folders' => function (Builder $query) {
                $query->where('folder_id', null);
            },
        ]);

        return new TaskResource($task);
    }

    public function billet(Course $course)
    {
        $tasks = Task::where('course_id', $course->id)->where('is_published', 0)->orderBy('created_at', 'desc')->get();

        return TaskResource::collection($tasks);
    }

    public function store(StoreRequest $request, Course $course)
    {
        $credentials = $request->validated();

        $task = Task::create([
            'title' => null,
            'description' => null,
            'course_id' => $course->id,
            'type_id' => $credentials['type'],
            'is_published' => 0
        ]);

        $path = '/storage/courses/course_' . $course->id . '/task_' . $task->id . '/files';

        Folder::create([
            'task_id' => $task->id,
            'user_id' => $course->leader_id,
            'folder_id' => null,
            'original_name' => 'files',
            'folder_path' => $path,
        ]);

        Storage::disk('public')->makeDirectory(mb_substr($path, 9));

        if ($credentials['type'] == 1) {
            foreach ($course->members->where("id", "!=", $course->leader_id) as $member) {
                $decision = $task->decisions()->create([
                    'user_id' => $member->id,
                    'task_id' => $task->id,
                    'description' => null,
                    'completed_id' => 1,
                ]);

                $path = '/storage/courses/course_' . $course->id . '/task_' . $task->id . '/users/user_' . $member->id;

                Folder::create([
                    'task_id' => $task->id,
                    'decision_id' => $decision->id,
                    'user_id' => $member->id,
                    'folder_id' => null,
                    'original_name' => 'user_' . $member->id,
                    'folder_path' => $path,
                ]);
            };
        }

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

        if (auth()->user()->id == $course->leader_id) {
            $task->loadMissing([
                'decisions' => function (Builder $query) {
                    $query->where('completed_id', '!=', 1)->with(['completed']);
                }
            ]);
        }

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

        $path = 'courses/course_' . $course->id . '/task_' . $task->id;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->deleteDirectory($path);
        }

        return response([]);
    }
}
