<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Storage;

/**
 * @property static $imageProperty
 */
trait FullPathImageTrait
{
    /**
     * @return Attribute
     */
    protected function fullFilePath(): Attribute
    {
        return new Attribute(
            get: fn() => Storage::url($this->{static::$imageProperty ?? 'image'})
        );
    }
}
