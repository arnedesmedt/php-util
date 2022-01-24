<?php

declare(strict_types=1);

namespace ADS\Util;

use ADS\Util\Exception\StringUtilException;
use RuntimeException;

use function array_filter;
use function array_map;
use function array_search;
use function array_slice;
use function explode;
use function floatval;
use function implode;
use function intval;
use function is_numeric;
use function lcfirst;
use function preg_replace;
use function reset;
use function sort;
use function sprintf;
use function str_contains;
use function str_replace;
use function strrchr;
use function strtolower;
use function substr;
use function trim;
use function ucwords;

final class StringUtil
{
    public const ENTITY_PREFIX_NAMES = [
        'Entity',
        'Model',
        'Projection',
    ];

    public static function camelize(string $string, string $delimiter = '_', bool $pascal = false): string
    {
        $result = str_replace($delimiter, '', ucwords($string, $delimiter));

        return $pascal ? $result : lcfirst($result);
    }

    public static function decamelize(string $string, string $delimiter = '_'): string
    {
        $regex = [
            '/([a-z\d])([A-Z])/',
            sprintf('/([^%s])([A-Z][a-z])/', $delimiter),
        ];

        $replaced = preg_replace($regex, '$1_$2', $string);

        if ($replaced === null) {
            throw StringUtilException::couldNotDecamilize($string);
        }

        return strtolower($replaced);
    }

    public static function slug(string $slug): string
    {
        /** @var string $trim */
        $trim = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);

        return strtolower(trim($trim, '-'));
    }

    /**
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public static function castFromString(string $string)
    {
        switch (true) {
            case $string === 'false':
                return false;

            case $string === 'true':
                return true;

            case is_numeric($string) && str_contains($string, '.'):
                return floatval($string);

            case is_numeric($string):
                return intval($string);
        }

        return $string;
    }

    /**
     * @param array|string[] $prefixes
     */
    public static function entityNamespaceFromClassName(
        string $className,
        array $prefixes = self::ENTITY_PREFIX_NAMES
    ): string {
        $positions = self::positionsOfPrefixes($className, $prefixes);
        $resourceNameParts = explode('\\', $className);

        if (! empty($positions)) {
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
                $className
            )
        );
    }

    /**
     * @param array|string[] $prefixes
     */
    public static function entityNameFromClassName(
        string $className,
        array $prefixes = self::ENTITY_PREFIX_NAMES
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
                $className
            )
        );
    }

    /**
     * @param array<string> $prefixes
     *
     * @return array<int>
     */
    private static function positionsOfPrefixes(
        string $className,
        array $prefixes = self::ENTITY_PREFIX_NAMES
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
                $prefixes
            )
        );

        sort($positions);

        return $positions;
    }

    public static function classBasename(string $className): string
    {
        $strrchr = strrchr($className, '\\');

        if ($strrchr === false) {
            return $className;
        }

        return substr($strrchr, 1);
    }
}
