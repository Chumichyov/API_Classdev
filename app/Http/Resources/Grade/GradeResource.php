<?php

namespace App\Http\Resources\Grade;

use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'symbol' => $this->symbol
        ];
    }
}
