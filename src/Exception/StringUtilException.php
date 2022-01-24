<?php

declare(strict_types=1);

namespace ADS\Util\Exception;

use Exception;

use function sprintf;

final class StringUtilException extends Exception
{
    /**
     * @return static
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public static function couldNotDecamilize(string $string)
    {
        return new static(
            sprintf(
                'It\'s not possible to decamilize string \'%s\'.',
                $string
            )
        );
    }
}
