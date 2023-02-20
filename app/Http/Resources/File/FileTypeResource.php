<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Resources\Json\JsonResource;

class FileTypeResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'title' => $this->title,
        ];
    }
}
