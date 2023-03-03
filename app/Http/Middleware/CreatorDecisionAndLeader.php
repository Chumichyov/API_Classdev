<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Decision;
use Closure;
use Illuminate\Http\Request;

class CreatorDecisionAndLeader
{
    public function handle(Request $request, Closure $next)
    {
        $decision = $request->route('decision');
        $course = $request->route('course');

        gettype($decision) == 'string' ? $decision = Decision::findOrFail($decision) : '';
        gettype($course) == 'string' ? $course = Course::findOrFail($course) : '';

        if (auth()->user()->id == $decision->user_id || auth()->user()->id == $course->leader_id) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Forbidden'], 403);
    }
}
