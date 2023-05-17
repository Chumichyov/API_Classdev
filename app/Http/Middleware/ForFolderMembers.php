<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Decision;
use App\Models\Folder;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ForFolderMembers
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
        $folder = $request->route('folder');
        $task = $request->route('task');
        $course = $request->route('course');
        $decision = $request->route('decision');

        gettype($folder) == 'string' ? $folder = Folder::findOrFail($folder) : '';
        gettype($task) == 'string' ? $task = Task::findOrFail($task) : '';
        gettype($course) == 'string' ? $course = Course::findOrFail($course) : '';
        gettype($decision) == 'string' ? $decision = Decision::findOrFail($decision) : '';

        if ((Route::currentRouteName() == 'folder.mainFolder' || Route::currentRouteName() == 'folder.taskStore' || Route::currentRouteName() == 'folder.decisionStore' || Route::currentRouteName() == 'decision.decisionMainShow') && $task->course->members->where('id', auth()->user()->id)->first()) {
            return $next($request);
        } else if (!is_null($decision) && $folder->decision->id == $decision->id && $folder->task->id == $task->id && $task->course->id == $course->id) {
            return $next($request);
        } else if ($folder->task && !is_null($folder->task->course->members->where('id', auth()->user()->id)->first()) && $folder->task->id == $task->id && $folder->task->course->id == $course->id || $folder->decision && !is_null($folder->decision->task->course->members->where('id', auth()->user()->id)->first()) && $folder->task->id == $folder->decision->task->id && $folder->task->course->id == $course->id) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Запрещено'], 403);
    }
}
