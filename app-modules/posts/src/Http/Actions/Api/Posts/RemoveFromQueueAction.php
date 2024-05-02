<?php
declare(strict_types=1);

namespace Modules\Posts\Http\Actions\Api\Posts;

use App\Http\Enums\HttpStatusesEnum;
use App\Http\Responses\ErrorJsonResponse;
use App\Http\Responses\SuccessJsonResponse;
use Illuminate\Contracts\Support\Responsable;
use Modules\Auth\Enums\NotificationStatusesEnum;
use Modules\Auth\Events\NotificationEvent;
use Modules\Posts\DTO\Responses\RemoveFromQueueDTO;
use Modules\Posts\Enums\StatusesEnum;
use Modules\Queue\Services\RedisQueueManager;
use Throwable;

class RemoveFromQueueAction
{
    /**
     * @param RedisQueueManager $redisQueueManager
     */
    public function __construct(
        protected RedisQueueManager $redisQueueManager,
    )
    {
    }

    /**
     * @param string $jobUuid
     * @param int $userId
     * @return Responsable
     */
    public function __invoke(string $jobUuid, int $userId): Responsable
    {
        try {
            $job = $this->redisQueueManager->getJobByUUID($jobUuid);

            if (!$job) {
                return new ErrorJsonResponse(
                    message: 'Job not found',
                    status: HttpStatusesEnum::NOT_FOUND,
                );
            }

            $this->redisQueueManager->deleteFromQueue($job->original);

            $job->job->data->command->post->update([
                'status' => StatusesEnum::CANCELED->value,
            ]);

            event(new NotificationEvent('Successfully removed from queue', NotificationStatusesEnum::SUCCESS, $userId));

            return new SuccessJsonResponse(
                data: new RemoveFromQueueDTO($jobUuid),
            );
        } catch (Throwable $exception) {
            return new ErrorJsonResponse(
                message: $exception->getMessage(),
                status: HttpStatusesEnum::INTERNAL_SERVER_ERROR,
            );
        }
    }
}
