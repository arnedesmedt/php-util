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
use function is_string;
use function ksort;
use function range;
use function str_starts_with;
use function strlen;
use function substr;

/** @SuppressWarnings(PHPMD.BooleanArgumentFlag) */
final class ArrayUtil
{
    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    private static function process(
        array $array,
        Closure|null $keyClosure = null,
        Closure|null $valueClosure = null,
    ): array {
        $processedArray = [];

        foreach ($array as $key => $value) {
            $processedArray[$keyClosure ? $keyClosure($key) : $key] = $valueClosure ? $valueClosure($value) : $value;
        }

        return $processedArray;
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    private static function processRecursive(
        array $array,
        Closure|null $keyClosure = null,
        Closure|null $valueClosure = null,
    ): array {
        $processedArray = [];

        foreach ($array as $key => $value) {
            $isStdClass = $value instanceof stdClass;

            $value = $isStdClass ? (array) $value : $value;

            if (is_array($value)) {
                $value = self::processRecursive($value, $keyClosure, $valueClosure);
            }

            $value = $isStdClass ? (object) $value : $value;

            $processedArray[$keyClosure ? $keyClosure($key) : $key] = $valueClosure ? $valueClosure($value) : $value;
        }

        return $processedArray;
    }

    private static function processMethod(bool $recursive): string
    {
        return $recursive ? 'processRecursive' : 'process';
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    public static function toCamelCasedKeys(array $array, bool $recursive = false, string $delimiter = '_'): array
    {
        $method = self::processMethod($recursive);

        return self::{$method}(
            $array,
            static fn ($key) => is_int($key) ? $key : StringUtil::camelize($key, $delimiter),
        );
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    public static function toCamelCasedValues(array $array, bool $recursive = false, string $delimiters = '_'): array
    {
        $method = self::processMethod($recursive);

        return self::{$method}(
            $array,
            valueClosure: static fn ($value) => is_string($value)
                ? StringUtil::camelize($value, $delimiters)
                : $value,
        );
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    public static function toSnakeCasedKeys(array $array, bool $recursive = false, string $splitDelimiters = ''): array
    {
        $method = self::processMethod($recursive);

        return self::{$method}(
            $array,
            static fn ($key) => is_int($key) ? $key : StringUtil::decamelize($key, splitDelimiters: $splitDelimiters),
        );
    }

    /**
     * @param array<int|string, mixed> $array
     *
     * @return array<int|string, mixed>
     */
    public static function toSnakeCasedValues(
        array $array,
        bool $recursive = false,
        string $splitDelimiters = '',
    ): array {
        $method = self::processMethod($recursive);

        return self::{$method}(
            $array,
            valueClosure: static fn ($value) => is_string($value)
                ? StringUtil::decamelize($value, splitDelimiters: $splitDelimiters)
                : $value,
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
        return self::filter($array, static fn ($value) => ! empty($value), $recursive);
    }

    /**
     * @param array<mixed> $array
     *
     * @return array<mixed>
     */
    private static function filter(array $array, Closure $filter, bool $recursive = true): array
    {
        if (! $recursive) {
            return array_filter($array, $filter);
        }

        return array_filter(
            self::processRecursive(
                $array,
                valueClosure: static function ($value) use ($filter) {
                    if (is_array($value)) {
                        $value = array_filter(
                            $value,
                            $filter,
                        );
                    }

                    return $value;
                },
            ),
            $filter,
        );
    }

    /** @param array<mixed> $array */
    public static function isAssociative(array $array): bool
    {
        return ! empty($array) && array_keys($array) !== range(0, count($array) - 1);
    }

    /** @param array<mixed> $array */
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
        $method = self::processMethod($recursive);

        return self::{$method}(
            $array,
            static fn ($value) => is_int($value) || ! str_starts_with((string) $value, $prefix)
                ? $value
                : substr((string) $value, strlen($prefix)),
        );
    }
}
