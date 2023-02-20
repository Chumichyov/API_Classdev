<?php

namespace App\Http\Resources\Decision;

use App\Http\Resources\File\FileResource;
use App\Http\Resources\Grade\GradeResource;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class DecisionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user' => new UserResource(User::find($this->user_id)),
            'task' => new TaskResource($this->whenLoaded('task')),
            'description' => $this->description,
            'grade' => new GradeResource($this->whenLoaded('grade')),
            'files' => FileResource::collection($this->whenLoaded('files')),
        ];
    }
}
