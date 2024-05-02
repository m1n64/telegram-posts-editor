<?php
declare(strict_types=1);

namespace Modules\Posts\Http\Actions\Api\Posts;

use Modules\Auth\Enums\NotificationStatusesEnum;
use Modules\Auth\Events\NotificationEvent;
use Modules\Posts\DTO\PostDTO;
use Modules\Posts\Services\PostService;

class SaveAction
{
    /**
     * @param PostService $postService
     */
    public function __construct(
        public PostService $postService,
    )
    {
    }

    /**
     * @param PostDTO $dto
     * @param int $userId
     * @return int
     */
    public function __invoke(PostDTO $dto, int $userId): int
    {
        $post = $this->postService->save($dto);

        event(new NotificationEvent('Successfully saved', NotificationStatusesEnum::SUCCESS, $userId));
        return $post->id;
    }
}
