<?php

namespace App\Http\Middleware;

use App\Models\Decision;
use Closure;
use Illuminate\Http\Request;

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

        gettype($decision) == 'string' ? $decision = Decision::findOrFail($decision) : '';

        if (!is_null($decision->task->course->members->where('id', auth()->user()->id)->first())) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Forbidden'], 403);
    }
}
