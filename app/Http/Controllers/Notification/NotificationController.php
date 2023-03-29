<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications;

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
