<?php
declare(strict_types=1);

namespace Modules\Queue\DTO;

class RedisQueueJobDTO
{
    /**
     * @param \stdClass $job
     * @param string $original
     */
    public function __construct(
        public \stdClass $job,
        public string $original,
    )
    {
    }
}
