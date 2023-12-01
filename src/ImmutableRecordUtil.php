<?php

declare(strict_types=1);

namespace ADS\Util;

use function is_a;
use function is_object;
use function method_exists;

final class ImmutableRecordUtil
{
    public static function toScalar(mixed $data): mixed
    {
        if (
            is_object($data)
            && is_a($data, 'EventEngine\Data\ImmutableRecord')
            && method_exists($data, 'toArray')
        ) {
            return $data->toArray(); // @phpstan-ignore-line
        }

        return null;
    }
}
