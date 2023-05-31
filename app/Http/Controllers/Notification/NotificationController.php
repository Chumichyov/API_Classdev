<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\IndexRequest;
use App\Http\Resources\Notification\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function default()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->take(10)->get();
        $notifications->loadMissing([
            'course',
            'task' => function (Builder $query) {
                $query->with([
                    'folders',
                    'files'
                ]);
            },
            'decision',
            'user',
        ]);

        $haveUnread = Notification::where('recipient_id', auth()->user()->id)->where('isRead', 0)->count() > 0;

        return response([
            "data" => [
                'notifications' => NotificationResource::collection($notifications),
                'haveUnread' => $haveUnread,
            ]
        ]);
    }

    public function read(Notification $notification)
    {
        $notification->update([
            'isRead' => 1,
        ]);

        $haveUnread = Notification::where('recipient_id', auth()->user()->id)->where('isRead', 0)->count() > 0;

        return response([
            "data" => [
                'notification' => new NotificationResource($notification),
                'haveUnread' => $haveUnread,
            ]
        ]);
    }


    public function index(IndexRequest $request)
    {
        $credentials  = $request->validated();
        if ($credentials['type'] == 'Inbox') {
            //All

            if (isset($credentials['search'])) {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('message', 'LIKE', "%{$credentials['search']}%")->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('message', 'LIKE', "%{$credentials['search']}%")->paginate(15, ['*'], 'page', $credentials['page']);
            } else {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->paginate(15, ['*'], 'page', $credentials['page']);
                }
            }
        } else if ($credentials['type'] == 'Course') {
            //Course

            if (isset($credentials['search'])) {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('message', 'LIKE', "%{$credentials['search']}%")->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('message', 'LIKE', "%{$credentials['search']}%")->paginate(15, ['*'], 'page', $credentials['page']);
            } else {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->paginate(15, ['*'], 'page', $credentials['page']);
            }
        } else if ($credentials['type'] == 'Task') {
            //Task

            if (isset($credentials['search'])) {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '=', null)->where('message', 'LIKE', "%{$credentials['search']}%")->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '=', null)->where('message', 'LIKE', "%{$credentials['search']}%")->paginate(15, ['*'], 'page', $credentials['page']);
            } else {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '=', null)->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '=', null)->paginate(15, ['*'], 'page', $credentials['page']);
            }
        } else if ($credentials['type'] == 'Decision') {
            //Decision

            if (isset($credentials['search'])) {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '!=', null)->where('message', 'LIKE', "%{$credentials['search']}%")->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '!=', null)->where('message', 'LIKE', "%{$credentials['search']}%")->paginate(15, ['*'], 'page', $credentials['page']);
            } else {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '!=', null)->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision_id', '!=', null)->paginate(15, ['*'], 'page', $credentials['page']);
            }
        } else if ($credentials['type'] == 'Messenger') {
            //Messenger

            // $notifications = Notification::orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->where('course_id', '!=', null)->where('task_id', '!=', null)->where('decision', '!=', null)->paginate(10, ['*'], 'page', $credentials['page']);
        } else {
            if (isset($credentials['search'])) {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('message', 'LIKE', "%{$credentials['search']}%")->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('message', 'LIKE', "%{$credentials['search']}%")->paginate(15, ['*'], 'page', $credentials['page']);
            } else {
                if ($credentials['all'] == "false") {
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->where('isRead', 0)->paginate(15, ['*'], 'page', $credentials['page']);
                } else
                    $notifications = Notification::orderBy('created_at', 'desc')->where('recipient_id', auth()->user()->id)->paginate(15, ['*'], 'page', $credentials['page']);
            }
        }

        $notifications->loadMissing([
            'course',
            'task' => function (Builder $query) {
                $query->with('folders');
            },
            'decision',
            'user',
            'decision',
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
