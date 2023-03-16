<?php

namespace App\Http\Controllers\Decision;

use App\Http\Controllers\Controller;
use App\Http\Requests\Decision\StoreRequest;
use App\Http\Requests\Decision\UpdateRequest;
use App\Http\Resources\Decision\DecisionResource;
use App\Models\Course;
use App\Models\Decision;
use App\Models\Task;
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
        $decision->loadMissing([
            'task',
            'grade',
            'files'
        ]);

        return new DecisionResource($decision);
    }

    public function update(UpdateRequest $request, Course $course, Task $task, Decision $decision)
    {
        $credentials = $request->validated();

        $decision->update($credentials);

        return new DecisionResource($decision);
    }

    public function destroy(Course $course, Task $task, Decision $decision)
    {
        $decision->delete();

        return response([]);
    }
}
