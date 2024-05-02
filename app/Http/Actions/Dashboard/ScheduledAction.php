<?php
declare(strict_types=1);

namespace App\Http\Actions\Dashboard;

use Modules\Queue\DTO\RedisQueueJobDTO;
use Modules\Queue\Services\RedisQueueManager;

class ScheduledAction
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
     * @param int $channelId
     * @return array
     */
    public function __invoke(int $channelId): array
    {
        return array_map(function (RedisQueueJobDTO $job) {
            return [
                'uuid' => $job->job->uuid,
                'post' => $job->job->data->command->post,
                'delay' => $job->job->data->command->delay,
            ];
        }, $this->redisQueueManager->filterJobs(function ($job) use ($channelId) {
            return $job->data->command->post->telegram_key_id == $channelId;
        }));
    }
}
