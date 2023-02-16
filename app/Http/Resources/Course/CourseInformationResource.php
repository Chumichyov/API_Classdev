<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseInformationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'course_id' => $this->course_id,
            'photo_path' => $this->photo_path,
            'photo_name' => $this->photo_name,
            'code' => $this->code,
            'link' => $this->link,
        ];
    }
}
