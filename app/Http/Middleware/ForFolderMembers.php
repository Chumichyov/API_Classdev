<?php

namespace App\Http\Middleware;

use App\Models\Folder;
use Closure;
use Illuminate\Http\Request;

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

        gettype($folder) == 'string' ? $folder = Folder::findOrFail($folder) : '';

        if (!is_null($folder->task->course->members->where('id', auth()->user()->id)->first())) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Forbidden'], 403);
    }
}
