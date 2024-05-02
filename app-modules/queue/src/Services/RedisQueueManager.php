<?php
declare(strict_types=1);

namespace Modules\Queue\Services;

use Illuminate\Contracts\Redis\Factory as RedisFactory;
use \Illuminate\Support\Facades\Redis;
use Modules\Queue\DTO\RedisQueueJobDTO;

class RedisQueueManager
{
    protected const LARAVEL_QUEUE_NAME = 'queues:default:delayed';

    /**
     * @return array
     * @throws \RedisException
     */
    public function getAllJobs(): array
    {
        $activeJobs = Redis::zrange(self::LARAVEL_QUEUE_NAME, 0, -1);
        $jobs = [];

        foreach ($activeJobs as $activeJob) {
            $job = $this->unserializeJob($activeJob);

            $jobs[] = new RedisQueueJobDTO($job, $activeJob);
        }

        return $jobs;
    }

    /**
     * @param callable $callback
     * @return array
     */
    public function filterJobs(callable $callback): array
    {
        $activeJobs = Redis::zrange(self::LARAVEL_QUEUE_NAME, 0, -1);
        $jobs = [];

        foreach ($activeJobs as $activeJob) {
            $job = $this->unserializeJob($activeJob);

            if ($callback($job)) {
                $jobs[] = new RedisQueueJobDTO($job, $activeJob);
            }
        }

        return $jobs;
    }

    /**
     * @param string $uuid
     * @return RedisQueueJobDTO|null
     */
    public function getJobByUUID(string $uuid): ?RedisQueueJobDTO
    {
        $activeJobs = Redis::zrange(self::LARAVEL_QUEUE_NAME, 0, -1);

        foreach ($activeJobs as $activeJob) {
            $job = $this->unserializeJob($activeJob);

            if ($job->uuid == $uuid) {
                return new RedisQueueJobDTO($job, $activeJob);
            }
        }

        return null;
    }

    /**
     * @param string $job
     * @return void
     */
    public function deleteFromQueue(string $job): void
    {
        Redis::zrem(self::LARAVEL_QUEUE_NAME, $job);
    }

    /**
     * @param string $job
     * @return \stdClass
     */
    protected function unserializeJob(string $job): \stdClass
    {
        $activeJob = json_decode($job);
        $activeJob->data->command = unserialize($activeJob->data->command);

        return $activeJob;
    }
}
