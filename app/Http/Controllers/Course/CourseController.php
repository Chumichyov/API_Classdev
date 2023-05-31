<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\ConnectionRequest;
use App\Http\Requests\Course\IndexRequest;
use App\Http\Requests\Course\InvitationRequest;
use App\Http\Requests\Course\StoreImageRequest;
use App\Http\Requests\Course\StoreRequest;
use App\Http\Requests\Course\UpdateRequest;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\User\UserResource;
use App\Models\Course;
use App\Models\CourseInformation;
use App\Models\CourseUser;
use App\Models\Messenger;
use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(IndexRequest $request)
    {
        $credentials = $request->validated();

        if (isset($credentials['search'])) {
            $courses = Course::whereHas('members', function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            })->where('title', 'LIKE', "%{$credentials['search']}%")->get();

            return CourseResource::collection($courses);
        }

        return CourseResource::collection(auth()->user()->courses);
    }

    public function store(StoreRequest $request)
    {
        try {
            $credentials = $request->validated();
            $credentials['leader_id'] = auth()->user()->id;
            $course = Course::create($credentials);

            CourseUser::create([
                'user_id' => auth()->user()->id,
                'course_id' => $course->id,
                'role_id' => 2,
            ]);

            $faker = Faker::create();

            do {
                $link = $faker->bothify('?????##???###?????##');
            } while (CourseInformation::where('link', $link)->first() !== null);

            do {
                $code = $faker->bothify('??#?#?');
            } while (CourseInformation::where('code', $code)->first() !== null);

            do {
                $background = $faker->numberBetween(1, 3);
            } while (!Storage::exists('/public/backgrounds/' . $background . '.png'));

            CourseInformation::create([
                'course_id' => $course->id,
                'image_path' => '/storage/backgrounds/' . $background . '.png',
                'image_name' => $background . '.png',
                'image_extension' => 'png',
                'code' => strtoupper($code),
                'link' => $link
            ]);

            $path = '/public/courses/course_' . $course->id;

            //Main course folder
            Storage::makeDirectory($path);

            return new CourseResource($course);
        } catch (Exception $e) {
            return response(['error_message' => 'Непредвиденная ошибка. Пожалуйста, повторите попытку.']);
        }
    }


    public function members(Course $course)
    {
        $members = $course->members->loadMissing([
            'student' => function (Builder $query) use ($course) {
                $query->where('course_id', $course->id);
            },
            'decisions' => function (Builder $query) {
                $query->orderBy('created_at', 'desc');
            },
        ]);

        return UserResource::collection($members);
    }

    public function show(Course $course)
    {
        $course->loadMissing([
            'information',
            'members'
        ]);

        $course->members->loadMissing([
            'student' => function (Builder $query) use ($course) {
                $query->where('course_id', $course->id);
            },
            'decisions' => function (Builder $query) {
                $query->orderBy('created_at', 'desc');
            },
        ]);

        return new CourseResource($course);
    }

    public function update(UpdateRequest $request, Course $course)
    {
        $credentials = $request->validated();
        $course->update($credentials);

        return new CourseResource($course);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response([]);
    }

    public function connection(ConnectionRequest $request)
    {
        $credentials = $request->validated();
        $link = $request->link;

        if (isset($credentials['code'])) {
            $course = CourseInformation::where('code', $credentials['code'])->first();
        }

        if ($link) {
            $course = CourseInformation::where('link', $link)->first();
        }

        if (!is_null($course)) {
            if (!is_null($course->course->members->where('id', auth()->user()->id)->first())) {
                return response(['error_message' => 'Вы уже состоите в данном курсе']);
            }

            CourseUser::create([
                'course_id' => $course->course_id,
                'user_id' => auth()->user()->id,
            ]);

            $messenger = Messenger::create([
                'course_id' => $course->course_id,
                'teacher_id' => $course->course->leader_id,
                'student_id' => auth()->user()->id,
            ]);

            $notification = Notification::create([
                'type_id' => 2,
                'recipient_id' => $course->course->leader_id,
                'user_id' => auth()->user()->id,
                'course_id' => $course->course_id,
                'message' => 'В курсе ' . $course->course->title . ' появился новый участник: ' . auth()->user()->name . ' ' . auth()->user()->surname
            ]);

            return new CourseResource($course->course);
        }

        return response()->json(['error_message' => 'Запрещено'], 403);
    }

    public function leave(Course $course)
    {
        $member = CourseUser::where('user_id', auth()->user()->id)->where('course_id', $course->id)->first();

        if ($member) {
            $member->delete();
            $messenger = Messenger::where('course_id', $course->id)->where('student_id', auth()->user()->id)->first();

            if ($messenger != null)
                $messenger->delete();
        }

        return response(['success_message' => 'Вы успешно покинули курс']);
    }

    public function expel(Course $course, User $user)
    {
        $member = CourseUser::where('user_id', $user->id)->where('course_id', $course->id)->first();

        if ($member) {
            $member->delete();
        }

        $messenger = Messenger::where('course_id', $course->id)->where('student_id', $user->id)->first();
        if ($messenger != null)
            $messenger->delete();

        return response(['success_message' => 'Вы успешно выгнали участника']);
    }
}
