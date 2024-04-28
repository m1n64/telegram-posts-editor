<?php
declare(strict_types=1);

namespace App\DTO;

class ErrorResponseDTO
{
    /**
     * @param string $message
     * @param mixed|null $data
     */
    public function __construct(
        public string $message,
        public mixed $data = null,
    )
    {
    }
}
