<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\IndexRequest;
use App\Http\Resources\Notification\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(IndexRequest $request)
    {
        $credentials  = $request->validated();
        if ($credentials['type'] == 'Inbox') {
            //All
            $notifications = Notification::orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->paginate(10, ['*'], 'page', $credentials['page']);
        } else if ($credentials['type'] == 'Course') {
            //Course
            $notifications = Notification::orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->where('course_id', '!=', null)->paginate(10, ['*'], 'page', $credentials['page']);
        } else if ($credentials['type'] == 'Task') {
            //Task
            $notifications = Notification::orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '=', null)->paginate(10, ['*'], 'page', $credentials['page']);
        } else if ($credentials['type'] == 'Decision') {
            //Task
            $notifications = Notification::orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '!=', null)->paginate(10, ['*'], 'page', $credentials['page']);
        } else if ($credentials['type'] == 'Messenger') {
            //Task
            // $notifications = Notification::orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision', '!=', null)->paginate(10, ['*'], 'page', $credentials['page']);
        } else {
            $notifications = Notification::orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->paginate(10, ['*'], 'page', $credentials['page']);
        }

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
