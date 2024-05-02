<?php
declare(strict_types=1);

namespace Modules\Posts\DTO\Responses;

class RemoveFromQueueDTO
{
    /**
     * @param string $uuid
     */
    public function __construct(
        public string $uuid,
    )
    {
    }
}
