<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\User\UserResource;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'group' => $this->group,
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'leader' => new UserResource(User::find($this->leader_id)),
            'leader_id' => $this->leader_id,
            'information' => new CourseInformationResource($this->whenLoaded('information')),
        ];
    }
}
