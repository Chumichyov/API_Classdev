<?php

namespace App\Http\Resources\Folder;

use App\Http\Resources\Decision\DecisionResource;
use App\Http\Resources\File\FileResource;
use App\Models\Decision;
use App\Models\DecisionFolder;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'decision' => new DecisionResource($this->whenLoaded('decision')),
            'folders' => FolderResource::collection($this->whenLoaded('folders')),
            'files' => FileResource::collection($this->whenLoaded('files')),
            'is_main' => $this->is_main,
            'folder_path' => $this->folder_path,
        ];
    }
}
