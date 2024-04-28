<?php
declare(strict_types=1);

namespace Modules\Posts\DTO;

use Modules\Posts\Enums\AttachmentTypesEnum;

class AttachmentDTO
{
    /**
     * @param string $hash
     * @param string $tmpPath
     * @param AttachmentTypesEnum $type
     */
    public function __construct(
        public string $hash,
        public string $tmpPath,
        public AttachmentTypesEnum $type = AttachmentTypesEnum::IMAGE,
    )
    {
    }
}
