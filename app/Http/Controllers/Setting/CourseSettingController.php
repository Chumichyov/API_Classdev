<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\StoreImageRequest;
use App\Http\Resources\Course\CourseInformationResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Invitation\InvitationResource;
use App\Models\Course;
use App\Models\CourseInformation;
use App\Models\Invitation;
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

    public function getInvitations(Course $course)
    {
        return InvitationResource::collection(Invitation::where('course_id', $course->id)->get());
    }

    public function changeCode(Course $course)
    {
        $code = $course->information->code;
        $faker = Faker::create();

        do {
            $newCode = strtoupper($faker->bothify('??#?#?'));
        } while (CourseInformation::where('code', $newCode)->first() !== null);

        $course->information->update([
            'code' => $newCode,
        ]);
        return response([
            "data" => [
                'code' =>  $course->information->code,
            ]
        ]);
    }

    public function changeLink(Course $course)
    {
        $link = $course->information->link;
        $faker = Faker::create();

        do {
            $newLink = $faker->bothify('?????##???###?????##');
        } while (CourseInformation::where('link', $newLink)->first() !== null);

        $course->information->update([
            'link' => $newLink,
        ]);

        return response([
            "data" => [
                'link' =>  $course->information->link,
            ]
        ]);
    }
}
