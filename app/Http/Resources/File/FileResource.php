<?php

namespace App\Http\Resources\File;

use App\Http\Resources\Decision\DecisionResource;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\User\UserResource;
use App\Models\FileExtension;
use App\Models\FileType;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'task' => new TaskResource($this->whenLoaded('task')),
            'decision' => new DecisionResource($this->whenLoaded('decision')),
            'user' => new UserResource($this->whenLoaded('user')),
            'extension' => new FileExtensionResource(FileExtension::find($this->file_extension_id)),
            'original_name' => $this->original_name,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
        ];
    }
}
