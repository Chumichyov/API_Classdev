<?php

namespace App\Http\Resources\User;

use App\Models\Role;
use App\Models\UserInformation;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'information' => new UserInformationResource(UserInformation::find($this->id)),
            'role' => new RoleResource(Role::find($this->role_id)),
        ];
    }
}
