<?php

namespace App\Http\Resources\File;

use App\Http\Resources\Decision\DecisionResource;
use App\Http\Resources\Task\TaskResource;
use App\Models\FileExtension;
use App\Models\FileType;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'file_type' => new FileTypeResource(FileType::find($this->file_type_id)),
            'task' => new TaskResource($this->whenLoaded('task')),
            'decision' => new DecisionResource($this->whenLoaded('decision')),
            'extension' => new FileExtensionResource(FileExtension::find($this->file_extension_id)),
            'original_name' => $this->original_name,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
        ];
    }
}
