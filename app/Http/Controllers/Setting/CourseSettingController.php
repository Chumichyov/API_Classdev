<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreImageRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
use App\Models\CourseInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class CourseSettingController extends Controller
{
    public function storeBackground(StoreImageRequest $request, Course $course)
    {
        $credentials = $request->validated();

        $imageName = md5(microtime()) . '.' . $credentials['image']->extension();

        if ($course->information->custom_image == 1) {
            Storage::disk('public')->delete(substr($course->information->image_path, 9));
        }

        $path = 'courses/course_' . $course->id . '/background';
        $credentials['image']->storeAs('public/' . $path, $imageName);

        $information = CourseInformation::where('course_id', $course->id)
            ->update([
                'image_path' => '/storage/' . $path . '/' . $imageName,
                'image_name' => $imageName,
                'image_extension' => $credentials['image']->extension(),
                'custom_image' => 1,
            ]);

        return new CourseResource($course->fresh()->loadMissing('information'));
    }

    public function deleteBackground(Course $course)
    {
        if ($course->information->custom_image == 1) {
            Storage::disk('public')->deleteDirectory('courses/course_' . $course->id . '/background/');

            $faker = Faker::create();

            do {
                $background = $faker->numberBetween(1, 3);
            } while (!Storage::exists('/public/backgrounds/' . $background . '.png'));

            $course->information->update([
                'image_path' => '/storage/backgrounds/' . $background . '.png',
                'image_name' => $background . '.png',
                'image_extension' => 'png',
                'custom_image' => 0,
            ]);
        }

        return new CourseResource($course->fresh()->loadMissing('information'));
    }
}
