<?php

namespace App\Http\Resources\File;

use App\Models\Mode;
use Illuminate\Http\Resources\Json\JsonResource;

class FileExtensionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'extension' => $this->extension,
            'mode' => new ModeResource(Mode::find($this->mode_id)),
        ];
    }
}
