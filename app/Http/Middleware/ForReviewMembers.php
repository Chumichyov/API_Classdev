<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Decision;
use App\Models\File;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;

class ForReviewMembers
{
    public function handle(Request $request, Closure $next)
    {
        $file = $request->route('file');
        $course = $request->route('course');
        $task = $request->route('task');
        $decision = $request->route('decision');

        gettype($file) == 'string' ? $file = File::findOrFail($file) : '';
        gettype($course) == 'string' ? $course = Course::findOrFail($course) : '';
        gettype($task) == 'string' ? $task = Task::findOrFail($task) : '';
        gettype($decision) == 'string' ? $decision = Decision::findOrFail($decision) : '';

        if (!is_null($file->task->course->members->where('id', auth()->user()->id)->first()) && $file->task->id == $task->id && $file->task->course->id == $course->id && $file->decision->id == $decision->id && $decision->task->id == $task->id) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Запрещено'], 403);
    }
}
