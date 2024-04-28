<?php
declare(strict_types=1);

namespace Modules\Posts\Http\Actions\Api\Posts;

use Modules\Posts\DTO\PostDTO;
use Modules\Posts\Enums\StatusesEnum;
use Modules\Posts\Jobs\SendPostJob;

class SendAction extends SaveAction
{

    /**
     * @param PostDTO $dto
     * @return int
     */
    public function __invoke(PostDTO $dto): int
    {
        $post = $this->postService->save($dto);
        $post->update(['status' => StatusesEnum::PENDING->value]);

        SendPostJob::dispatch($post)->delay(now()->diffInSeconds($dto->publish_date));

        return $post->id;
    }
}
