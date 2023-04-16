<?php

namespace App\Http\Resources\Notification;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Decision\DecisionResource;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\User\UserResource;
use App\Models\NotificationType;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            // 'user' => new UserResource(User::find($this->user_id)),
            'id' => $this->id,
            'type' => new NotificationTypeResource(NotificationType::find($this->type_id)),
            'course' => new CourseResource($this->whenLoaded('course')),
            'task' => new TaskResource($this->whenLoaded('task')),
            'decision' => new DecisionResource($this->whenLoaded('decision')),
            'message' => $this->message,
            'isRead' => $this->isRead,
            'created_at' => $this->created_at,
        ];
    }
}
