<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     public static $wrap = null;

    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
            'token' => $this->whenNotNull($this->token),
            'created_at' => Carbon::parse($this->created_at)->format('d F Y H:i:s'),
        ];
    }
}
