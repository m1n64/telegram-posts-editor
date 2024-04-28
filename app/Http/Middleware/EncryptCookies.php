<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as BaseEncryptCookies;

class EncryptCookies extends BaseEncryptCookies
{
    protected $except = [
        '__token'
    ];

    protected static $neverEncrypt = [
        '__token',
    ];
}
