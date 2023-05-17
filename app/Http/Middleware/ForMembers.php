<?php

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;

class ForMembers
{
    public function handle(Request $request, Closure $next)
    {
        $course = $request->route('course');

        gettype($course) == 'string' ? $course = Course::findOrFail($course) : '';

        if (!is_null($course->members->where('id', auth()->user()->id)->first())) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Запрещено'], 403);
    }
}
