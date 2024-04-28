<?php
declare(strict_types=1);

namespace Modules\Posts\DTO\Responses;

class PostStatusDTO
{
    /**
     * @param int $id
     * @param string $title
     * @param string $type
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $type,
    )
    {
    }
}
