<?php

namespace App\Http\Resources\Invitation;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
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
            'course_id' => $this->course_id,
            'user_id' => new UserResource(User::find($this->user_id)),
        ];
    }
}
