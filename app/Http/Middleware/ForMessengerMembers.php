<?php

namespace App\Http\Middleware;

use App\Models\Messenger;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class ForMessengerMembers
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

        if (Route::currentRouteName('messenger.index') || Route::currentRouteName('messenger.search')) {
            return $next($request);
        }

        $messenger = $request->route('messenger');
        gettype($messenger) == 'string' ? $messenger = Messenger::findOrFail($messenger) : '';

        if ($messenger->student_id == auth()->user()->id || $messenger->teacher_id == auth()->user()->id) {
            return $next($request);
        }

        return response()->json(['error_message' => 'Запрещено'], 403);
    }
}
