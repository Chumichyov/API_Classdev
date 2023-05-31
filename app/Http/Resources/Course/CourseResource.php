<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Messenger\MessengerResource;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\User\UserResource;
use App\Models\Course;
use App\Models\Messenger;
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
            'members' => UserResource::collection($this->whenLoaded('members')),
            'leader' => new UserResource(User::find($this->leader_id)),
            'leader_id' => $this->leader_id,
            'messenger' => auth()->user()->id != $this->leader_id ? Messenger::where('course_id', $this->id)->where('student_id', auth()->user()->id)->first() : null,
            'information' => new CourseInformationResource($this->whenLoaded('information')),
        ];
    }
}
