<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        return CourseResource::collection(auth()->user()->courses->loadMissing('information'));
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

        //Change photo
        if (Arr::exists($credentials, 'image')) {
            $imageName = time() . '.' . $credentials['image']->extension();

            if ($course->information->imagePath != 'http://dummyimage.com/500x237') {
                Storage::disk('public')->delete(substr($course->info->imagePath, 9));
            }

            $path = 'courses/course-' . $course->id . 'image';
            $credentials['image']->storeAs('public/' . $path, $imageName);

            CourseInformation::where('course_id', $course->id)
                ->update([
                    'photo_path' => '/storage/' . $path . '/' . $imageName,
                    'photo_name' => $imageName,
                ]);

            return new CourseResource($course->loadMissing('information'));
        }
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response([]);
    }
}
