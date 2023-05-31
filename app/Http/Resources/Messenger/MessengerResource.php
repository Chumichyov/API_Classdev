<?php

namespace App\Http\Resources\Messenger;

use App\Http\Resources\User\UserResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MessengerResource extends JsonResource
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
            'teacher' => new UserResource(User::find($this->teacher_id)),
            'student' => new UserResource(User::find($this->student_id)),
            'last_message' => new MessageResource(Message::where('messenger_id', $this->id)->orderBy('id', 'desc')->first()),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
        ];
    }
}
