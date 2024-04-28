<?php
declare(strict_types=1);

namespace Modules\Posts\Enums;

enum AttachmentTypesEnum: int
{
    case IMAGE = 1;
    case VIDEO = 2;
    case AUDIO = 3;
    case FILE = 4;

    /**
     * @return string
     */
    public function title(): string
    {
        return match ($this) {
            self::IMAGE => 'Image',
            self::VIDEO => 'Video',
            self::AUDIO => 'Audio',
            self::FILE => 'File',
        };
    }
}
