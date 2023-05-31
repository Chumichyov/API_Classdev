<?php

namespace App\Http\Resources\Folder;

use App\Http\Resources\Decision\DecisionResource;
use App\Http\Resources\File\FileResource;
use App\Models\Decision;
use App\Models\DecisionFolder;
use App\Models\Folder;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
{
    public function toArray($request)
    {
        $folder = Folder::find($this->id);

        return [
            'id' => $this->id,
            'decision' => new DecisionResource($this->whenLoaded('decision')),
            'folders' => FolderResource::collection($this->whenLoaded('folders')),
            'belonging' => new FolderResource($this->whenLoaded('folder')),
            'files' => FileResource::collection($this->whenLoaded('files')),
            'original_name' => $this->original_name,
            'folder_path' => $this->folder_path,
            'reviews' => $this->reviews->count(),
            "is_empty" => $folder->folders->count() == 0 && $folder->files->count() == 0,
        ];
    }
}
