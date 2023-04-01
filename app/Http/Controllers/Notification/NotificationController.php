<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->get();
        $notifications->loadMissing([
            'course',
            'task',
            'decision'
        ]);

        return NotificationResource::collection($notifications);
    }

    public function store(Request $request)
    {
    }

    public function show(Notification $notification)
    {
    }

    public function update(Request $request, Notification $notification)
    {
    }

    public function destroy(Notification $notification)
    {
    }
}
