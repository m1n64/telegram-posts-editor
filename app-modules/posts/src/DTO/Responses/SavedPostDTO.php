<?php
declare(strict_types=1);

namespace Modules\Posts\DTO\Responses;

class SavedPostDTO
{
    /**
     * @param int $id
     */
    public function __construct(
        public int $id,
    )
    {
    }
}
