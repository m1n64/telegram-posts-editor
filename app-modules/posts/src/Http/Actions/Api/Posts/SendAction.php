<?php
declare(strict_types=1);

namespace Modules\Posts\Http\Actions\Api\Posts;

use Modules\Auth\Enums\NotificationStatusesEnum;
use Modules\Auth\Events\NotificationEvent;
use Modules\Posts\DTO\PostDTO;
use Modules\Posts\Enums\StatusesEnum;
use Modules\Posts\Jobs\SendPostJob;

class SendAction extends SaveAction
{

    /**
     * @param PostDTO $dto
     * @param int $userId
     * @return int
     */
    public function __invoke(PostDTO $dto, int $userId): int
    {
        $post = $this->postService->save($dto);
        $post->update(['status' => StatusesEnum::PENDING->value]);

        SendPostJob::dispatch($post)->delay(now()->diffInSeconds($dto->publish_date));

        $notifyMessage = 'Successfully saved and ' . ($dto->publish_date ? 'scheduled' : 'sent to channel');

        event(new NotificationEvent($notifyMessage, NotificationStatusesEnum::SUCCESS, $userId));

        return $post->id;
    }
}
