<?php

declare(strict_types=1);

namespace ADS\Util;

use Closure;
use stdClass;

use function array_filter;
use function array_keys;
use function count;
use function is_array;
use function is_int;
use function ksort;
use function range;
use function str_starts_with;
use function strlen;
use function substr;

final class ArrayUtil
{
    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    private static function process(
        array $array,
        ?Closure $keyClosure = null,
        ?Closure $valueClosure = null,
        bool $recursive = false
    ): array {
        $processedArray = [];

        foreach ($array as $key => $value) {
            if ($recursive) {
                $isStdClass = $value instanceof stdClass;

                if ($isStdClass) {
                    $value = (array) $value;
                }

                if (is_array($value)) {
                    $value = self::process($value, $keyClosure, $valueClosure, $recursive);
                }

                $value = $isStdClass ? (object) $value : $value;
            }

            $processedArray[$keyClosure ? $keyClosure($key) : $key] = $valueClosure ? $valueClosure($value) : $value;
        }

        return $processedArray;
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    public static function toCamelCasedKeys(array $array, bool $recursive = false): array
    {
        return self::process(
            $array,
            static fn ($key) => is_int($key) ? $key : StringUtil::camelize($key),
            null,
            $recursive
        );
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    public static function toSnakeCasedKeys(array $array, bool $recursive = false): array
    {
        return self::process(
            $array,
            static fn ($key) => is_int($key) ? $key : StringUtil::decamelize($key),
            null,
            $recursive
        );
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    public static function toSnakeCasedValues(array $array, bool $recursive = false): array
    {
        return self::process(
            $array,
            null,
            static fn ($value) => is_int($value) ? $value : StringUtil::decamelize($value),
            $recursive
        );
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    public static function toCamelCasedValues(array $array, bool $recursive = false): array
    {
        return self::process(
            $array,
            null,
            static fn ($value) => is_int($value) ? $value : StringUtil::camelize($value),
            $recursive
        );
    }

    /**
     * @param array<mixed> $array
     *
     * @return array<mixed>
     */
    public static function rejectNullValues(array $array, bool $recursive = true): array
    {
        return self::filter($array, static fn ($value) => $value !== null, $recursive);
    }

    /**
     * @param array<mixed> $array
     *
     * @return array<mixed>
     */
    public static function rejectEmptyArrayValues(array $array, bool $recursive = true): array
    {
        return self::filter($array, static fn ($value) => ! is_array($value) || ! empty($value), $recursive);
    }

    /**
     * @param array<mixed> $array
     *
     * @return array<mixed>
     */
    private static function filter(array $array, Closure $filter, bool $recursive = true): array
    {
        return array_filter(
            self::process(
                $array,
                null,
                static function ($value) use ($filter) {
                    if (is_array($value)) {
                        $value = array_filter(
                            $value,
                            $filter
                        );
                    }

                    return $value;
                },
                $recursive
            ),
            $filter
        );
    }

    /**
     * @param array<mixed> $array
     */
    public static function isAssociative(array $array): bool
    {
        if (empty($array)) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param array<mixed> $array
     */
    public static function ksortRecursive(array &$array): bool
    {
        foreach ($array as &$value) {
            if (! is_array($value)) {
                continue;
            }

            self::ksortRecursive($value);
        }

        return ksort($array);
    }

    /**
     * @param array<mixed> $array
     *
     * @return array<mixed>
     */
    public static function removePrefixFromKeys(array $array, string $prefix, bool $recursive = true): array
    {
        return self::process(
            $array,
            static fn ($value) => is_int($value) || ! str_starts_with($prefix, $value)
                ? $value
                : substr($value, strlen($prefix)),
            null,
            $recursive
        );
    }
}
