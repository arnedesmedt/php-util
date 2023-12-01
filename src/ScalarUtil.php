<?php

declare(strict_types=1);

namespace ADS\Util;

use function array_map;
use function is_array;

final class ScalarUtil
{
    public static function toScalar(mixed $data): mixed
    {
        $convertedData = ValueObjectUtil::toScalar($data) ?? ImmutableRecordUtil::toScalar($data);

        if ($convertedData !== null) {
            return $convertedData;
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
