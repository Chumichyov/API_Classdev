<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'course' => new CourseResource(Course::find($this->course_id)),
        ];
    }
}
