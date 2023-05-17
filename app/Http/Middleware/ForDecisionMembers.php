<?php

namespace App\Http\Middleware;

use App\Models\Decision;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ForDecisionMembers
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
        $decision = $request->route('decision');
        $task = $request->route('task');

        gettype($decision) == 'string' ? $decision = Decision::findOrFail($decision) : '';
        gettype($task) == 'string' ? $task = Task::findOrFail($task) : '';

        if (Route::currentRouteName() == 'decision.authShow') {
            $decision = Decision::where('task_id', $task->id)->where('user_id', auth()->user()->id)->first();
            if (is_null($decision))
                return $next($request);
        }

        if (!is_null($decision->task->course->members->where('id', auth()->user()->id)->first()) || (Route::currentRouteName() == 'decision.authShow' && !is_null($task->course->members->where('id', auth()->user()->id)->first()))) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Запрещено'], 403);
    }
}
