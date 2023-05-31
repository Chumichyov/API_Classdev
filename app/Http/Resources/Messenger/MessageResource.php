<?php

namespace App\Http\Resources\Messenger;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'id' => $this->id,
            'sender' => $this->sender_id,
            'recipient' => $this->recipient_id,
            'content' => $this->content,
            'created_at' => $this->created_at,
        ];
    }
}
