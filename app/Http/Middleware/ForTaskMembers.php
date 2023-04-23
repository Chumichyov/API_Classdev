<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ForTaskMembers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $task = $request->route('task');
        $course = $request->route('course');

        gettype($task) == 'string' ? $task = Task::findOrFail($task) : '';
        gettype($course) == 'string' ? $course = Course::findOrFail($course) : '';

        if ((Route::currentRouteName() == 'task.index' || Route::currentRouteName() == 'task.store') && $course->members->where('id', auth()->user()->id)->first()) {
            return $next($request);
        } else if (!is_null($task->course->members->where('id', auth()->user()->id)->first())) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Forbidden'], 403);
    }
}
