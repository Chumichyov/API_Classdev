<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\File\FileResource;
use App\Http\Resources\Folder\FolderResource;
use App\Models\Course;
use App\Models\Task;
use App\Models\TaskFile;
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
        $task = Task::find($this->id);
        $course = Course::find($this->course_id);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'course' => new CourseResource($this->whenLoaded('course')),
            'files' => FileResource::collection($this->whenLoaded('files')),
            'folders' => FolderResource::collection($this->whenLoaded('folders')),
            'type' => new TaskTypeResource($this->whenLoaded('type')),
            'is_completed' => $task->decisions->where('user_id', auth()->user()->id)->first() != null || $task->type->id == 2 || $course->leader_id == auth()->user()->id,
            'created_at' => $this->created_at->format('d-m-Y')
        ];
    }
}
