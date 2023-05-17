<?php

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;

class OnlyMember
{
    public function handle(Request $request, Closure $next)
    {
        $course = $request->route('course');
        gettype($course) == 'string' ? $course = Course::findOrFail($course) : '';

        if ($course->members->where('id', auth()->user()->id)->where('role_id', 1)->first()) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Запрещено'], 403);
    }
}
