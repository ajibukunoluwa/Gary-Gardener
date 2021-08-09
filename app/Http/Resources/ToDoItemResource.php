<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ToDoItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->loadMissing(['creator']);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'due_date' => $this->due_date,
            'due_date_for_humans' => optional($this->due_date)->diffForHumans(),
            'attachment_url' => $this->attachment_url,
            'is_complete' => $this->isComplete(),
            'completed_at' => $this->when($this->isComplete(), $this->completed_at),
            'creator' => new UserResource($this->creator),
        ];
    }
}
