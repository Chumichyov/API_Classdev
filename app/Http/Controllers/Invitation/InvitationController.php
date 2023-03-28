<?php

namespace App\Http\Controllers\Invitation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\InvitationRequest;
use App\Http\Resources\Invitation\InvitationResource;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function index()
    {
        return InvitationResource::collection(Invitation::where('user_id', auth()->user()->id)->get());
    }

    public function store(InvitationRequest $request, Course $course)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (!is_null($user)) {
            if (is_null(CourseUser::where('course_id', $course->id)->where('user_id', $user->id)->first())) {

                if (is_null(Invitation::where('course_id', $course->id)->where('user_id', $user->id)->first())) {

                    Invitation::create([
                        'course_id' => $course->id,
                        'user_id' => $user->id,
                    ]);

                    return response(['success_message' => 'Приглашение успешно отправлено']);
                }

                return response(['error_message' => 'Данный пользователь уже приглашен']);
            }

            return response(['error_message' => 'Данный пользователь является участником курса']);
        }

        return response(['error_message' => 'Данного пользователя не существует']);
    }

    public function accept(Invitation $invitation)
    {
        if ($invitation->user_id == auth()->user()->id) {
            CourseUser::create([
                'course_id' => $invitation->course_id,
                'user_id' => $invitation->user_id,
            ]);

            $invitation->delete();

            return response()->json(['success_message' => 'Вы успешно присоединились к курсу']);
        }

        return response()->json(['error_message' => 'Forbidden'], 403);
    }

    public function show(Invitation $invitation)
    {
    }

    public function update(Request $request, Invitation $invitation)
    {
    }

    public function destroy(Invitation $invitation)
    {
    }
}
