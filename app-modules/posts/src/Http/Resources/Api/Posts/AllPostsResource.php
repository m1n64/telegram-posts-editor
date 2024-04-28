<?php

namespace Modules\Posts\Http\Resources\Api\Posts;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Posts\DTO\Responses\PostStatusDTO;
use Modules\Posts\Enums\StatusesEnum;

class AllPostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $statusId = $this->status;
        $status = StatusesEnum::from($statusId);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content_decoded,
            'status' => new PostStatusDTO(
                $statusId,
                $status->name(),
                $status->type(),
            ),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i'),
            'publish_date' => $this->publish_date ? Carbon::parse($this->publish_date)->format('Y-m-d H:i') : null,
        ];
    }
}
