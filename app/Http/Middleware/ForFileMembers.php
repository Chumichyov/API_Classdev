<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\File;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ForFileMembers
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
        $file = $request->route('file');
        $course = $request->route('course');

        gettype($file) == 'string' ? $file = File::findOrFail($file) : '';
        gettype($course) == 'string' ? $course = Course::findOrFail($course) : '';

        if ((Route::currentRouteName() == 'file.index' || Route::currentRouteName() == 'file.store') && $course->members->where('id', auth()->user()->id)->first()) {
            return $next($request);
        } else if (!is_null($file->task->course->members->where('id', auth()->user()->id)->first())) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Forbidden'], 403);
    }
}
