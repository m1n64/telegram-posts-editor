<?php
declare(strict_types=1);

namespace Modules\Posts\Enums;

use phpDocumentor\Reflection\Types\Self_;

enum StatusesEnum: int
{
    case DRAFT = 0;

    case READY_TO_PUBLISH = 1;

    case PENDING = 2;

    case ERROR = 3;

    case PUBLISHED = 4;

    case CANCELED = 5;

    /**
     * @return string
     */
    public function name(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::READY_TO_PUBLISH => 'Ready to publish',
            self::PENDING => 'Pending',
            self::PUBLISHED => 'Published',
            self::ERROR => 'Error publish',
            self::CANCELED => 'Canceled',
        };
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return match ($this) {
            self::DRAFT => 'draft',
            self::READY_TO_PUBLISH => 'ready_to_publish',
            self::PENDING => 'pending',
            self::PUBLISHED => 'published',
            self::ERROR => 'error',
            self::CANCELED => 'canceled',
        };
    }
}
