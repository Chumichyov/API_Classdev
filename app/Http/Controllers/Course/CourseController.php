<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
use App\Models\CourseInformation;
use App\Models\CourseUser;
use Exception;
use Illuminate\Http\Request;
use Faker\Factory as Faker;


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

            CourseInformation::create([
                'course_id' => $course->id,
                'code' => $code,
                'link' => $link
            ]);

            return new CourseResource($course);
        } catch (Exception $e) {
            return response(['error_message' => 'Непредвиденная ошибка. Пожалуйста, повторите попытку.']);
        }
    }

    public function show(Course $course)
    {
        //
    }

    public function update(Request $request, Course $course)
    {
        //
    }

    public function destroy(Course $course)
    {
        //
    }
}
