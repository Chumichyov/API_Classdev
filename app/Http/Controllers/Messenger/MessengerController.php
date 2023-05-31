<?php

namespace App\Http\Controllers\Messenger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messenger\SearchRequest;
use App\Http\Requests\Messenger\StoreRequest;
use App\Http\Resources\Messenger\MessageResource;
use App\Http\Resources\Messenger\MessengerResource;
use App\Models\Course;
use App\Models\Message;
use App\Models\Messenger;
use App\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;

class MessengerController extends Controller
{
    public function index(Course $course)
    {
        // Student
        if ($course->leader_id != auth()->user()->id) {
            $messengers = Messenger::where('course_id', $course->id)->where('student_id', auth()->user()->id)->get();
            return MessengerResource::collection($messengers);
        }

        $messengers = Messenger::where('course_id', $course->id)->where('teacher_id', auth()->user()->id)->get();
        return MessengerResource::collection($messengers);
    }

    public function search(SearchRequest $request, Course $course)
    {
        $credentials = $request->validated();

        // Student
        if ($course->leader_id != auth()->user()->id) {
            $messenger = Messenger::where('course_id', $course->id)->where('student_id', auth()->user()->id)->get();
            return MessengerResource::collection($messenger);
        }

        if ($credentials['search'] != null) {
            $search = $credentials['search'];
            // $student = User::where('name', $credentials['search'])->orWhere('surname', $credentials['search'])->get();
            $messengers = Messenger::where('course_id', $course->id)->where('teacher_id', auth()->user()->id)->whereHas('student', function (Builder $query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")->orWhere('surname', 'LIKE', "%{$search}%");
            })->get();
            return MessengerResource::collection($messengers);
        }

        $messengers = Messenger::where('course_id', $course->id)->where('teacher_id', auth()->user()->id)->get();
        return MessengerResource::collection($messengers);
    }

    public function store(StoreRequest $request, Course $course, Messenger $messenger)
    {
        $credentials = $request->validated();

        $message = Message::create([
            'content' => $credentials['content'],
            'messenger_id' => $messenger->id,
            'sender_id' => auth()->user()->id,
            'recipient_id' => $messenger->student_id == auth()->user()->id ? $messenger->teacher_id : $messenger->student_id,
        ]);

        return new MessageResource($message);
    }

    public function show(Course $course, Messenger $messenger)
    {
        $messenger->loadMissing([
            'messages'
        ]);

        return new MessengerResource($messenger);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
