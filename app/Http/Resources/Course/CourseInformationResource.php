<?php

namespace App\Http\Resources\Course;

use App\Models\Course;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseInformationResource extends JsonResource
{
    public function toArray($request)
    {
        if (auth()->user()->id == Course::find($this->course->id)->leader_id) {
            // For leader course
            return [
                'image_path' => $this->image_path,
                'image_name' => $this->image_name,
                'image_extension' => $this->image_extension,
                'custom_image' => $this->custom_image,
                'code' => $this->code,
                'link' => $this->link,
            ];
        } else {
            // For member course
            return [
                'image_path' => $this->image_path,
                'image_name' => $this->image_name,
                'image_extension' => $this->image_extension,
                'custom_image' => $this->custom_image,
            ];
        }
    }
}
