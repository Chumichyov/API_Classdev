<?php

namespace App\Http\Controllers\Decision;

use App\Http\Controllers\Controller;
use App\Http\Requests\Decision\StoreRequest;
use App\Http\Requests\Decision\UpdateRequest;
use App\Http\Resources\Decision\DecisionResource;
use App\Http\Resources\File\FileResource;
use App\Http\Resources\Folder\FolderResource;
use App\Models\Course;
use App\Models\Decision;
use App\Models\File;
use App\Models\Folder;
use App\Models\Notification;
use App\Models\Task;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;

class DecisionController extends Controller
{
    public function index(Course $course, Task $task)
    {
        $decision = $task->decisions()->get();

        $decision->loadMissing([]);

        return DecisionResource::collection($decision);
    }

    public function store(StoreRequest $request, Course $course, Task $task)
    {
        $decision = $task->decisions()->where('user_id', auth()->user()->id)->first();

        if (is_null($decision)) {
            $credentials = $request->validated();

            $decision = Decision::create([
                'user_id' => auth()->user()->id,
                'task_id' => $task->id,
                'description' => isset($credentials['description']) ? $credentials['description'] : '',
            ]);
        }

        return new DecisionResource($decision);
    }

    public function show(Course $course, Task $task, Decision $decision)
    {
        $folder = Folder::where('decision_id', $decision->id)->where('folder_id', null)->where('user_id', $decision->user_id)->first();

        $files = File::where('decision_id', $decision->id)->where('folder_id', $folder->id)->get();
        $folders = Folder::where('folder_id', $folder->id)->where('decision_id', $decision->id)->get();

        $decision->loadMissing([
            'task',
            'grade',
            'completed'
        ]);

        return response(['data' => [
            'decision' => new DecisionResource($decision),
            'files' => FileResource::collection($files->loadMissing(['decision'])),
            'folders' => FolderResource::collection($folders->loadMissing(['decision'])),
            'folder' => new FolderResource($folder),
        ]]);
    }

    public function authShow(Course $course, Task $task)
    {
        $decision = $task->decisions->where('task_id', $task->id)->where('user_id', auth()->user()->id)->first();
        if (is_null($decision))
            return response(['data' => []]);

        $folder = Folder::where('decision_id', $decision->id)->where('folder_id', null)->where('user_id', auth()->user()->id)->first();

        $decision->loadMissing([
            'task',
            'grade',
            'completed',
        ]);

        if (!is_null($folder)) {
            $files = File::where('decision_id', $decision->id)->where('folder_id', $folder->id)->get();
            $folders = Folder::where('folder_id', $folder->id)->where('decision_id', $decision->id)->get();

            return response(['data' => [
                'decision' => new DecisionResource($decision),
                'files' => FileResource::collection($files->loadMissing(['decision'])),
                'folders' => FolderResource::collection($folders->loadMissing(['decision'])),
                'folder' => new FolderResource($folder),
            ]]);
        }

        return response(['data' => [
            'decision' => new DecisionResource($decision),
        ]]);
    }

    public function update(UpdateRequest $request, Course $course, Task $task, Decision $decision)
    {
        $credentials = $request->validated();
        if ($credentials['completed'] == null) {
            $decision->update([
                'description' => $credentials['description']
            ]);
        } else {
            $decision->update([
                'description' => $credentials['description'],
                'completed_id' => $credentials['completed']
            ]);

            if ($credentials['completed'] != 1) {
                Notification::create([
                    'type_id' => 4,
                    'recipient_id' => $course->leader_id,
                    'user_id' => auth()->user()->id,
                    'course_id' => $course->id,
                    'task_id' => $task->id,
                    'decision_id' => $decision->id,
                    'message' => "В курсе '" . $course->title . "' пользователь " . auth()->user()->name . ' ' . auth()->user()->surname . " сдал решение к заданию '" . $task->title . "'",
                ]);
            } else {
                Notification::where('course_id', $course->id)->where('task_id', $task->id)->where('decision_id', $decision->id)->where('recipient_id', $course->leader_id)->where('user_id', auth()->user()->id)->delete();
            }
        }

        $decision->loadMissing([
            'task',
            'grade',
            'completed'
        ]);

        return new DecisionResource($decision);
    }

    public function destroy(Course $course, Task $task, Decision $decision)
    {
        $decision->delete();

        return response([]);
    }
}
