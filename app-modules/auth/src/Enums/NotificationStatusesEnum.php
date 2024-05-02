<?php
declare(strict_types=1);

namespace Modules\Auth\Enums;

enum NotificationStatusesEnum: string
{
    case ERROR = 'error';
    case SUCCESS = 'success';

    case INFO = 'info';
}
