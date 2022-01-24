<?php

declare(strict_types=1);

namespace ADS\Util;

use function get_class;
use function gettype;
use function is_object;

final class Util
{
    /**
     * @param mixed $value
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public static function type($value): string
    {
        // phpcs:ignore SlevomatCodingStandard.Classes.ModernClassNameReference.ClassNameReferencedViaFunctionCall
        return is_object($value) ? get_class($value) : gettype($value);
    }
}
