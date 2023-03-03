<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Decision;
use Closure;
use Illuminate\Http\Request;

class CreatorDecision
{
    public function handle(Request $request, Closure $next)
    {
        $decision = $request->route('decision');

        gettype($decision) == 'string' ? $decision = Decision::findOrFail($decision) : '';

        if (auth()->user()->id == $decision->user_id) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Forbidden'], 403);
    }
}
