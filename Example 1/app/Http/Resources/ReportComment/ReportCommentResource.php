<?php

namespace App\Http\Resources\ReportComment;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportCommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'report_comment_id' => $this->id,
            'title' => $this->translateOrDefault(locale())?->title,
        ];
    }
}
