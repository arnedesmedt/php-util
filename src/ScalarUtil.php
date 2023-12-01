<?php

declare(strict_types=1);

namespace ADS\Util;

use function array_map;
use function is_a;
use function is_array;
use function is_object;
use function method_exists;

final class ScalarUtil
{
    public static function toScalar(mixed $data): mixed
    {
        if (
            is_object($data)
            && is_a($data, 'ADS\ValueObjects\ValueObject')
            && method_exists($data, 'toValue')
        ) {
            return $data->toValue(); // @phpstan-ignore-line
        }

        if (
            is_object($data)
            && is_a($data, 'EventEngine\Data\ImmutableRecord')
            && method_exists($data, 'toArray')
        ) {
            return $data->toArray(); // @phpstan-ignore-line
        }

        if (is_array($data)) {
            return array_map(
                static fn ($item) => self::toScalar($item),
                $data,
            );
        }

        return $data;
    }
}
