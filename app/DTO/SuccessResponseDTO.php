<?php
declare(strict_types=1);

namespace App\DTO;

class SuccessResponseDTO
{
    /**
     * @param mixed $data
     */
    public function __construct(
        public mixed $data,
    )
    {
    }
}
