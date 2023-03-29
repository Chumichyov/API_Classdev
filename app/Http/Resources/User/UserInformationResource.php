<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (auth()->user()->id == $this->user_id) {
            return [
                "bio" => $this->bio,
                "photo_path" => $this->photo_path
            ];
        } else {
            return [
                "photo_path" => $this->photo_path
            ];
        }
    }
}
