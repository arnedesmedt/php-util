<?php

declare(strict_types=1);

namespace ADS\Util;

use RuntimeException;
use Throwable;

use function array_filter;
use function array_map;
use function array_search;
use function array_slice;
use function explode;
use function floatval;
use function implode;
use function intval;
use function is_numeric;
use function json_decode;
use function lcfirst;
use function preg_match;
use function preg_quote;
use function preg_split;
use function reset;
use function sort;
use function sprintf;
use function str_contains;

use const JSON_THROW_ON_ERROR;

final class StringUtil
{
    public const ENTITY_PREFIX_NAMES = [
        'Entity',
        'Model',
        'Projection',
    ];

    public static function camelize(string $string, string $delimiters = '_', bool $pascal = false): string
    {
        $parts = preg_split(sprintf('/[%s]/', preg_quote($delimiters, '/')), $string);

        if ($parts === false) {
            throw new RuntimeException(
                sprintf('Could not camelize string \'%s\'.', $string),
            );
        }

        $result = implode('', array_map('ucfirst', $parts));

        return $pascal ? $result : lcfirst($result);
    }

    public static function decamelize(string $string, string $bindDelimiter = '_', string $splitDelimiters = ''): string
    {
        if (! empty($splitDelimiters)) {
            $string = self::camelize($string, $splitDelimiters);
        }

        $parts = preg_split('/(?<=[a-z0-9])(?=[A-Z])/x', $string);

        if ($parts === false) {
            throw new RuntimeException(
                sprintf('Could not decamelize string \'%s\'.', $string),
            );
        }

        $lowerParts = array_map(
            static fn (string $part) => preg_match('/[A-Z]/', lcfirst($part))
                ? $part
                : lcfirst($part),
            $parts,
        );

        return implode($bindDelimiter, $lowerParts);
    }

    public static function castFromString(string $string): mixed
    {
        switch (true) {
            case $string === 'false':
                return false;

            case $string === 'true':
                return true;

            case $string === 'null':
                return null;

            case is_numeric($string) && str_contains($string, '.'):
                return floatval($string);

            case is_numeric($string):
                return intval($string);
        }

        try {
            return json_decode($string, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return $string;
        }
    }

    /** @param array|string[] $prefixes */
    public static function entityNamespaceFromClassName(
        string $className,
        array $prefixes = self::ENTITY_PREFIX_NAMES,
    ): string {
        $positions = self::positionsOfPrefixes($className, $prefixes);

        if (! empty($positions)) {
            $resourceNameParts = explode('\\', $className);
            $position = reset($positions);
            $namespaceParts = array_slice($resourceNameParts, 0, $position + 1);

            if (empty($namespaceParts)) {
                return '\\';
            }

            return implode('\\', $namespaceParts);
        }

        throw new RuntimeException(
            sprintf(
                'Entity or Model name not found for class \'%s\'.',
                $className,
            ),
        );
    }

    /** @param array|string[] $prefixes */
    public static function entityNameFromClassName(
        string $className,
        array $prefixes = self::ENTITY_PREFIX_NAMES,
    ): string {
        // First try to find the short name by the class
        // Classes like *\Entity\Bank\* or *\Model\Bank\* will result in short name: Bank.
        $positions = self::positionsOfPrefixes($className, $prefixes);
        $resourceNameParts = explode('\\', $className);

        if (! empty($positions)) {
            foreach ($positions as $position) {
                $shortName = $resourceNameParts[$position + 1] ?? null;

                if ($shortName) {
                    return $shortName;
                }
            }
        }

        throw new RuntimeException(
            sprintf(
                'Entity or Model name not found for class \'%s\'.',
                $className,
            ),
        );
    }

    /**
     * @param array<string> $prefixes
     *
     * @return array<int>
     */
    private static function positionsOfPrefixes(
        string $className,
        array $prefixes = self::ENTITY_PREFIX_NAMES,
    ): array {
        $resourceNameParts = explode('\\', $className);

        $positions = array_filter(
            array_map(
                static function (string $prefixOfResourceName) use ($resourceNameParts) {
                    $position = array_search($prefixOfResourceName, $resourceNameParts);

                    if ($position === false) {
                        return null;
                    }

                    return $position;
                },
                $prefixes,
            ),
            static fn (int|null $position) => $position !== null,
        );

        sort($positions);

        return $positions;
    }
}
