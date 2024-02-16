<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit;

use ADS\Util\ArrayUtil;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** @SuppressWarnings(PHPMD.BooleanArgumentFlag) */
class ArrayUtilTest extends TestCase
{
    /** @return array<string, array<string, bool|array<string, string|array<string, string>>>> */
    public static function dataProviderCamelCaseKeys(): array
    {
        return [
            'test-camel-case-stays' => [
                'input' => ['camelCase' => 'camelCase'],
                'expected' => ['camelCase' => 'camelCase'],
            ],
            'test-from-snake-case' => [
                'input' => ['snake_case' => 'snake_case'],
                'expected' => ['snakeCase' => 'snake_case'],
            ],
            'test-from-kebab-case' => [
                'input' => ['kebab-case' => 'kebab-case'],
                'expected' => ['kebabCase' => 'kebab-case'],
            ],
            'test-from-space' => [
                'input' => ['space space' => 'space space'],
                'expected' => ['spaceSpace' => 'space space'],
            ],
            'test-no-recursive' => [
                'input' => ['no-recursive' => ['no-recursive' => 'no-recursive']],
                'expected' => ['noRecursive' => ['no-recursive' => 'no-recursive']],
            ],
            'test-recursive' => [
                'input' => ['yes-recursive' => ['yes-recursive' => 'yes-recursive']],
                'expected' => ['yesRecursive' => ['yesRecursive' => 'yes-recursive']],
                'recursive' => true,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @param array<string, mixed> $expected
     */
    #[DataProvider('dataProviderCamelCaseKeys')]
    public function testToCamelCasedKeys(array $input, array $expected, bool $recursive = false): void
    {
        $this->assertEquals($expected, ArrayUtil::toCamelCasedKeys($input, $recursive, '-_ '));
    }

    /** @return array<string, array<string, bool|array<string, string|array<string, string>>>> */
    public static function dataProviderCamelCaseValues(): array
    {
        return [
            'test-camel-case-stays' => [
                'input' => ['camelCase' => 'camelCase'],
                'expected' => ['camelCase' => 'camelCase'],
            ],
            'test-from-snake-case' => [
                'input' => ['snake_case' => 'snake_case'],
                'expected' => ['snake_case' => 'snakeCase'],
            ],
            'test-from-kebab-case' => [
                'input' => ['kebab-case' => 'kebab-case'],
                'expected' => ['kebab-case' => 'kebabCase'],
            ],
            'test-from-space' => [
                'input' => ['space space' => 'space space'],
                'expected' => ['space space' => 'spaceSpace'],
            ],
            'test-no-recursive' => [
                'input' => ['no-recursive' => ['no-recursive' => 'no-recursive']],
                'expected' => ['no-recursive' => ['no-recursive' => 'no-recursive']],
            ],
            'test-recursive' => [
                'input' => ['yes-recursive' => ['yes-recursive' => 'yes-recursive']],
                'expected' => ['yes-recursive' => ['yes-recursive' => 'yesRecursive']],
                'recursive' => true,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @param array<string, mixed> $expected
     */
    #[DataProvider('dataProviderCamelCaseValues')]
    public function testToCamelCasedValues(array $input, array $expected, bool $recursive = false): void
    {
        $this->assertEquals($expected, ArrayUtil::toCamelCasedValues($input, $recursive, '-_ '));
    }

    /** @return array<string, array<string, bool|array<string, string|array<string, string>>>> */
    public static function dataProviderSnakeCaseKeys(): array
    {
        return [
            'test-snake-case-stays' => [
                'input' => ['snake_case' => 'snake_case'],
                'expected' => ['snake_case' => 'snake_case'],
            ],
            'test-from-camel-case' => [
                'input' => ['camelCase' => 'camelCase'],
                'expected' => ['camel_case' => 'camelCase'],
            ],
            'test-from-kebab-case' => [
                'input' => ['kebab-case' => 'kebab-case'],
                'expected' => ['kebab_case' => 'kebab-case'],
            ],
            'test-from-space' => [
                'input' => ['space space' => 'space space'],
                'expected' => ['space_space' => 'space space'],
            ],
            'test-no-recursive' => [
                'input' => ['no-recursive' => ['no-recursive' => 'no-recursive']],
                'expected' => ['no_recursive' => ['no-recursive' => 'no-recursive']],
            ],
            'test-recursive' => [
                'input' => ['yes-recursive' => ['yes-recursive' => 'yes-recursive']],
                'expected' => ['yes_recursive' => ['yes_recursive' => 'yes-recursive']],
                'recursive' => true,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @param array<string, mixed> $expected
     */
    #[DataProvider('dataProviderSnakeCaseKeys')]
    public function testToSnakeCasedKeys(array $input, array $expected, bool $recursive = false): void
    {
        $this->assertEquals($expected, ArrayUtil::toSnakeCasedKeys($input, $recursive, '-_ '));
    }

    /** @return array<string, array<string, bool|array<string, string|array<string, string>>>> */
    public static function dataProviderSnakeCaseValues(): array
    {
        return [
            'test-snake-case-stays' => [
                'input' => ['snake_case' => 'snake_case'],
                'expected' => ['snake_case' => 'snake_case'],
            ],
            'test-from-camel-case' => [
                'input' => ['camelCase' => 'camelCase'],
                'expected' => ['camelCase' => 'camel_case'],
            ],
            'test-from-kebab-case' => [
                'input' => ['kebab-case' => 'kebab-case'],
                'expected' => ['kebab-case' => 'kebab_case'],
            ],
            'test-from-space' => [
                'input' => ['space space' => 'space space'],
                'expected' => ['space space' => 'space_space'],
            ],
            'test-no-recursive' => [
                'input' => ['no-recursive' => ['no-recursive' => 'no-recursive']],
                'expected' => ['no-recursive' => ['no-recursive' => 'no-recursive']],
            ],
            'test-recursive' => [
                'input' => ['yes-recursive' => ['yes-recursive' => 'yes-recursive']],
                'expected' => ['yes-recursive' => ['yes-recursive' => 'yes_recursive']],
                'recursive' => true,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @param array<string, mixed> $expected
     */
    #[DataProvider('dataProviderSnakeCaseValues')]
    public function testToSnakeCasedValues(array $input, array $expected, bool $recursive = false): void
    {
        $this->assertEquals($expected, ArrayUtil::toSnakeCasedValues($input, $recursive, '-_ '));
    }

    /** @return array<string, array<string, bool|array<mixed>>> */
    public static function dataProviderRejectNull(): array
    {
        return [
            'test-null-value' => [
                'input' => [null],
                'expected' => [],
            ],
            'test-empty-string' => [
                'input' => [''],
                'expected' => [''],
            ],
            'test-false-boolean' => [
                'input' => [false],
                'expected' => [false],
            ],
            'test-0-integer' => [
                'input' => [0],
                'expected' => [0],
            ],
            'test-empty-array' => [
                'input' => [[]],
                'expected' => [[]],
            ],
            'test-non-recursive' => [
                'input' => ['test' => [null]],
                'expected' => ['test' => [null]],
            ],
            'test-recursive' => [
                'input' => ['test' => [null]],
                'expected' => ['test' => []],
                'recursive' => true,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @param array<string, mixed> $expected
     */
    #[DataProvider('dataProviderRejectNull')]
    public function testRejectNullValues(array $input, array $expected, bool $recursive = false): void
    {
        $this->assertEquals($expected, ArrayUtil::rejectNullValues($input, $recursive));
    }

    /** @return array<string, array<string, bool|array<mixed>>> */
    public static function dataProviderRejectEmptyArrayValues(): array
    {
        return [
            'test-null-value' => [
                'input' => [null],
                'expected' => [],
            ],
            'test-empty-string' => [
                'input' => [''],
                'expected' => [],
            ],
            'test-false-boolean' => [
                'input' => [false],
                'expected' => [],
            ],
            'test-0-integer' => [
                'input' => [0],
                'expected' => [],
            ],
            'test-empty-array' => [
                'input' => [[]],
                'expected' => [],
            ],
            'test-non-recursive' => [
                'input' => ['test' => [null]],
                'expected' => ['test' => [null]],
            ],
            'test-recursive' => [
                'input' => ['test' => [null]],
                'expected' => [],
                'recursive' => true,
            ],
            'test-recursive-with-multiple-values' => [
                'input' => ['test' => [null, 'test']],
                'expected' => ['test' => [1 => 'test']],
                'recursive' => true,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @param array<string, mixed> $expected
     */
    #[DataProvider('dataProviderRejectEmptyArrayValues')]
    public function testRejectEmptyArrayValues(array $input, array $expected, bool $recursive = false): void
    {
        $this->assertEquals($expected, ArrayUtil::rejectEmptyArrayValues($input, $recursive));
    }

    /** @return array<string, array<string, bool|array<mixed>>> */
    public static function dataProviderIsAssociative(): array
    {
        return [
            'test-associative' => [
                'input' => ['test' => 'test'],
                'expected' => true,
            ],
            'test-non-associative' => [
                'input' => ['test'],
                'expected' => false,
            ],
            'test-empty' => [
                'input' => [],
                'expected' => false,
            ],
        ];
    }

    /** @param array<mixed> $input */
    #[DataProvider('dataProviderIsAssociative')]
    public function testIsAssociative(array $input, bool $expected): void
    {
        $this->assertEquals($expected, ArrayUtil::isAssociative($input));
    }

    /** @return array<string, array<string, bool|array<mixed>>> */
    public static function dataProviderKeySortRecursive(): array
    {
        return [
            'test-associative-no-sort' => [
                'input' => ['a' => 'a', 'b' => 'b', 'c' => 'c'],
                'expected' => ['a' => 'a', 'b' => 'b', 'c' => 'c'],
            ],
            'test-associative-sort' => [
                'input' => ['b' => 'b', 'c' => 'c', 'a' => 'a'],
                'expected' => ['a' => 'a', 'b' => 'b', 'c' => 'c'],
            ],
            'test-associative-recursive' => [
                'input' => ['b' => 'b', 'c' => 'c', 'a' => ['e' => 'e', 'f' => 'f', 'd' => 'd']],
                'expected' => ['a' => ['d' => 'd', 'e' => 'e', 'f' => 'f'], 'b' => 'b', 'c' => 'c'],
            ],
            'test-numeric-no-sort' => [
                'input' => [0 => 0, 1 => 1, 2 => 2],
                'expected' => [0 => 0, 1 => 1, 2 => 2],
            ],
            'test-numeric-sort' => [
                'input' => [1 => 1, 2 => 2, 0 => 0],
                'expected' => [0 => 0, 1 => 1, 2 => 2],
            ],
            'test-numeric-recursive' => [
                'input' => [1 => 1, 2 => 2, 0 => [4 => 4, 5 => 5, 3 => 3]],
                'expected' => [0 => [3 => 3, 4 => 4, 5 => 5], 1 => 1, 2 => 2],
            ],
            'test-mixed-no-sort' => [
                'input' => [0 => 0, 2 => 2, 'b' => 'b'],
                'expected' => [0 => 0, 2 => 2, 'b' => 'b'],
            ],
            'test-mixed-sort' => [
                'input' => ['b' => 'b', 2 => 2, 0 => 0],
                'expected' => [0 => 0, 2 => 2, 'b' => 'b'],
            ],
            'test-mixed-recursive' => [
                'input' => ['b' => 'b', 2 => 2, 0 => ['e' => 'e', 5 => 5, 3 => 3]],
                'expected' => [0 => [3 => 3, 5 => 5, 'e' => 'e'], 2 => 2, 'b' => 'b'],
            ],
        ];
    }

    /**
     * @param array<mixed> $input
     * @param array<mixed> $expected
     */
    #[DataProvider('dataProviderKeySortRecursive')]
    public function testKeySortRecursive(array $input, array $expected): void
    {
        ArrayUtil::ksortRecursive($input);
        $this->assertEquals($expected, $input);
    }

    /** @return array<string, array<string, bool|array<mixed>|string>> */
    public static function dataProviderRemovePrefixesFromKeys(): array
    {
        return [
            'test-remove-none' => [
                'input' => ['test' => 'test', 'anders' => 'anders'],
                'prefix' => 'nothing',
                'expected' => ['test' => 'test', 'anders' => 'anders'],
            ],
            'test-remove-prefix' => [
                'input' => ['testje' => 'testje', 'test' => 'test', 'anders' => 'anders'],
                'prefix' => 'test',
                'expected' => ['je' => 'testje', '' => 'test', 'anders' => 'anders'],
            ],
            'test-remove-prefix-with-numeric' => [
                'input' => ['testje' => 'testje', 'test' => 'test', 0 => 1],
                'prefix' => 'test',
                'expected' => ['je' => 'testje', '' => 'test', 0 => 1],
            ],
            'test-remove-prefix-no-recursive' => [
                'input' => ['testje' => 'testje', 'test' => ['testje' => 'testje', 'test' => 'test']],
                'prefix' => 'test',
                'expected' => ['je' => 'testje', '' => ['testje' => 'testje', 'test' => 'test']],
            ],
            'test-remove-prefix-recursive' => [
                'input' => ['testje' => 'testje', 'test' => ['testje' => 'testje', 'test' => 'test']],
                'prefix' => 'test',
                'expected' => ['je' => 'testje', '' => ['je' => 'testje', '' => 'test']],
                'recursive' => true,
            ],
        ];
    }

    /**
     * @param array<mixed> $input
     * @param array<mixed> $expected
     */
    #[DataProvider('dataProviderRemovePrefixesFromKeys')]
    public function testRemovePrefixesFromKeys(
        array $input,
        string $prefix,
        array $expected,
        bool $recursive = false,
    ): void {
        $this->assertEquals($expected, ArrayUtil::removePrefixFromKeys($input, $prefix, $recursive));
    }

    /** @return array<string, array<string, bool|array<mixed>|string>> */
    public static function dataProviderRemoveSuffixesFromKeys(): array
    {
        return [
            'test-remove-none' => [
                'input' => ['test' => 'test', 'anders' => 'anders'],
                'suffix' => 'nothing',
                'expected' => ['test' => 'test', 'anders' => 'anders'],
            ],
            'test-remove-suffix' => [
                'input' => ['testje' => 'testje', 'je' => 'test', 'anders' => 'anders'],
                'suffix' => 'je',
                'expected' => ['test' => 'testje', '' => 'test', 'anders' => 'anders'],
            ],
            'test-remove-suffix-with-numeric' => [
                'input' => ['testje' => 'testje', 'je' => 'test', 0 => 1],
                'suffix' => 'je',
                'expected' => ['test' => 'testje', '' => 'test', 0 => 1],
            ],
            'test-remove-suffix-no-recursive' => [
                'input' => ['testje' => 'testje', 'je' => ['testje' => 'testje', 'test' => 'test']],
                'suffix' => 'je',
                'expected' => ['test' => 'testje', '' => ['testje' => 'testje', 'test' => 'test']],
            ],
            'test-remove-suffix-recursive' => [
                'input' => ['testje' => 'testje', 'je' => ['testje' => 'testje', 'je' => 'test']],
                'suffix' => 'je',
                'expected' => ['test' => 'testje', '' => ['test' => 'testje', '' => 'test']],
                'recursive' => true,
            ],
        ];
    }

    /**
     * @param array<mixed> $input
     * @param array<mixed> $expected
     */
    #[DataProvider('dataProviderRemoveSuffixesFromKeys')]
    public function testRemoveSuffixesFromKeys(
        array $input,
        string $suffix,
        array $expected,
        bool $recursive = false,
    ): void {
        $this->assertEquals($expected, ArrayUtil::removeSuffixFromKeys($input, $suffix, $recursive));
    }
}
