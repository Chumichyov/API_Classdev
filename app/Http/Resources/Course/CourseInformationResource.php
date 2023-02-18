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
                'photo_path' => $this->photo_path,
                'photo_name' => $this->photo_name,
                'code' => $this->code,
                'link' => $this->link,
            ];
        } else {
            // For member course
            return [
                'photo_path' => $this->photo_path,
                'photo_name' => $this->photo_name,
            ];
        }
    }
}
