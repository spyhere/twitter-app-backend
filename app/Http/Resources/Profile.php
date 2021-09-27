<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->profile->id,
            'user_id' => $this->profile->user_id,
            'first_name' => $this->profile->first_name,
            'last_name' => $this->profile->last_name,
            'avatar' => $this->profile->relative_avatar_url,
            'verified' => !!$this->email_verified_at,
            'created_at' => $this->profile->created_at,
            'updated_at' => $this->profile->updated_at,
        ];
    }
}
