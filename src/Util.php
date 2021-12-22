<?php

declare(strict_types=1);

namespace ADS\Util;

use function gettype;
use function is_object;

final class Util
{
    public static function type(mixed $value): string
    {
        return is_object($value) ? $value::class : gettype($value);
    }
}
