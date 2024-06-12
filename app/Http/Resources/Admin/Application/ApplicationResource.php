<?php

namespace App\Http\Resources\Admin\Application;

use App\Http\Resources\Admin\Job\JobResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'user' => UserResource::make($this->user),
            'job' => JobResource::make($this->job),
            'cover_letter' => $this->cover_letter,
            'cv_path' => $this->cv_path,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
