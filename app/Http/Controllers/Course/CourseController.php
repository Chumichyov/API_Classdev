<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\ConnectionRequest;
use App\Http\Requests\Course\InvitationRequest;
use App\Http\Requests\Course\StoreImageRequest;
use App\Http\Requests\Course\StoreRequest;
use App\Http\Requests\Course\UpdateRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
use App\Models\CourseInformation;
use App\Models\CourseUser;
use Exception;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
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
                'code' => $code,
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

    public function show(Course $course)
    {
        return new CourseResource($course->loadMissing('information'));
    }

    public function update(UpdateRequest $request, Course $course)
    {
        $credentials = $request->validated();

        //Change title and description
        if (Arr::exists($credentials, 'title')) {
            $course->update([
                'title' => $credentials['title'],
                'description' => $credentials['description'],
            ]);

            return new CourseResource($course->loadMissing('information'));
        }
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
                'course_id' => $course->id,
                'user_id' => auth()->user()->id,
            ]);
        }

        return response(['success_message' => 'Вы успешно присоединились к курсу']);
    }

    public function leave(Course $course)
    {
        $member = CourseUser::where('user_id', auth()->user()->id)->where('course_id', $course->id)->first();

        if ($member) {
            $member->delete();
        }

        return response(['success_message' => 'Вы успешно покинули курс']);
    }
}
