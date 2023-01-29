<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\RealEstate\RealEstateResource;
use App\Http\Resources\User\InfoUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = auth('sanctum')->user();
        return [
            'id' => $this->id,
            'comment_id' => $this->comment_id,
            'real_estate' => RealEstateResource::make($this->whenLoaded('real_estate')),
            'user' => InfoUserResource::make($this->whenLoaded('user')),
            'comment' => $this->comment,
            'date' => $this->created_at,
            'date_format' => formatDate('d-m-Y h:i A', $this->created_at),
            'diffForHumans' => $this->created_at->diffForHumans(),
            'children' => ChildrenResource::collection($this->childrenComments),
            'this_is_your_comment' => $user && $user->id == $this->user_id,
        ];
    }
}
