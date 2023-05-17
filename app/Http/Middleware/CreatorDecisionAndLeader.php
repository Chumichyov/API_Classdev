<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Decision;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CreatorDecisionAndLeader
{
    public function handle(Request $request, Closure $next)
    {
        $decision = $request->route('decision');
        $course = $request->route('course');
        $task = $request->route('task');

        gettype($decision) == 'string' ? $decision = Decision::findOrFail($decision) : '';
        gettype($course) == 'string' ? $course = Course::findOrFail($course) : '';
        gettype($task) == 'string' ? $task = Task::findOrFail($task) : '';

        if (Route::currentRouteName() == 'decision.authShow') {
            $decision = Decision::where('task_id', $task->id)->where('user_id', auth()->user()->id)->first();
            if (is_null($decision))
                return $next($request);
        }

        if (auth()->user()->id == $decision->user_id || auth()->user()->id == $course->leader_id) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Forbidden'], 403);
    }
}
