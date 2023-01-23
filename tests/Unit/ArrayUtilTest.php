<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit;

use ADS\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;

use function array_keys;

class ArrayUtilTest extends TestCase
{
    /** @return array<string, mixed> */
    public function theArray(): array
    {
        return [
            'camel_case1' => 'camel_case',
            'camel-case2' => 'camel-case',
            'camelCase3' => 'camel case',
            'camel case-both_works' => [
                'camel_case-both works' => 'camel_case',
                'camel-case1' => [
                    'camel_case' => 'camel_case',
                    'camel-case' => 'camel-case',
                    'camel case' => 'camel case',
                ],
                'camel case2' => 'camelCase',
            ],
        ];
    }

    /** @return array<string, mixed> */
    public function filterArray(): array
    {
        return [
            'a' => null,
            'b' => 'b',
            'c' => [
                'd' => null,
                'e' => 'e',
                'f' => 0,
                'g' => false,
                'h' => [],
            ],
            'd' => 0,
            'e' => '',
            'f' => 'false',
            'g' => [],
        ];
    }

    public function testToCamelCasedKeys(): void
    {
        /** @var array<string, array<string, array<string, string>>> $camelCasedKeysArray */
        $camelCasedKeysArray = ArrayUtil::toCamelCasedKeys($this->theArray(), true, '-_ ');

        $this->assertEquals(
            ['camelCase1', 'camelCase2', 'camelCase3', 'camelCaseBothWorks'],
            array_keys($camelCasedKeysArray),
        );

        $this->assertEquals(
            ['camelCaseBothWorks', 'camelCase1', 'camelCase2'],
            array_keys($camelCasedKeysArray['camelCaseBothWorks']),
        );

        $this->assertEquals(
            ['camelCase'],
            array_keys($camelCasedKeysArray['camelCaseBothWorks']['camelCase1']),
        );
    }

    public function testNotRecursiveToCamelCasedKeys(): void
    {
        /** @var array<string, array<string, array<string, string>>> $camelCasedKeysArray */
        $camelCasedKeysArray = ArrayUtil::toCamelCasedKeys($this->theArray(), false, '-_ ');

        $this->assertEquals(
            ['camelCase1', 'camelCase2', 'camelCase3', 'camelCaseBothWorks'],
            array_keys($camelCasedKeysArray),
        );

        $this->assertEquals(
            ['camel_case-both works', 'camel-case1', 'camel case2'],
            array_keys($camelCasedKeysArray['camelCaseBothWorks']),
        );
    }

    public function testToCamelCasedValues(): void
    {
        /** @var array<string, array<string, array<string, string>>> $camelCasedValues */
        $camelCasedValues = ArrayUtil::toCamelCasedValues($this->theArray(), true, '-_ ');

        $this->assertEquals('camelCase', $camelCasedValues['camel_case1']);
        $this->assertEquals('camelCase', $camelCasedValues['camel-case2']);
        $this->assertEquals('camelCase', $camelCasedValues['camelCase3']);
        $this->assertEquals('camelCase', $camelCasedValues['camel case-both_works']['camel_case-both works']);
    }

    public function testToSnakeCasedKeys(): void
    {
        /** @var array<string, array<string, array<string, string>>> $snakeCasedKeys */
        $snakeCasedKeys = ArrayUtil::toSnakeCasedKeys($this->theArray(), true, '-_ ');

        $this->assertEquals(
            ['camel_case1', 'camel_case2', 'camel_case3', 'camel_case_both_works'],
            array_keys($snakeCasedKeys),
        );

        $this->assertEquals(
            ['camel_case_both_works', 'camel_case1', 'camel_case2'],
            array_keys($snakeCasedKeys['camel_case_both_works']),
        );

        $this->assertEquals(
            ['camel_case'],
            array_keys($snakeCasedKeys['camel_case_both_works']['camel_case1']),
        );
    }

    public function testNotRecursiveToSnakeCasedKeys(): void
    {
        /** @var array<string, array<string, array<string, string>>> $snakeCasedKeys */
        $snakeCasedKeys = ArrayUtil::toSnakeCasedKeys($this->theArray(), false, '-_ ');

        $this->assertEquals(
            ['camel_case1', 'camel_case2', 'camel_case3', 'camel_case_both_works'],
            array_keys($snakeCasedKeys),
        );

        $this->assertEquals(
            ['camel_case-both works', 'camel-case1', 'camel case2'],
            array_keys($snakeCasedKeys['camel_case_both_works']),
        );
    }

    public function testToSnakeCasedValues(): void
    {
        /** @var array<string, array<string, array<string, string>>> $snakeCasedValues */
        $snakeCasedValues = ArrayUtil::toSnakeCasedValues($this->theArray(), true, '-_ ');

        $this->assertEquals('camel_case', $snakeCasedValues['camel_case1']);
        $this->assertEquals('camel_case', $snakeCasedValues['camel-case2']);
        $this->assertEquals('camel_case', $snakeCasedValues['camelCase3']);
        $this->assertEquals('camel_case', $snakeCasedValues['camel case-both_works']['camel_case-both works']);
    }

    public function testRejectNullValues(): void
    {
        $expected = [
            'b' => 'b',
            'c' => [
                'e' => 'e',
                'f' => 0,
                'g' => false,
                'h' => [],
            ],
            'd' => 0,
            'e' => '',
            'f' => 'false',
            'g' => [],
        ];

        $this->assertEquals($expected, ArrayUtil::rejectNullValues($this->filterArray()));
    }

    public function testRejectEmptyArrayValues(): void
    {
        $expected = [
            'a' => null,
            'b' => 'b',
            'c' => [
                'd' => null,
                'e' => 'e',
                'f' => 0,
                'g' => false,
            ],
            'd' => 0,
            'e' => '',
            'f' => 'false',
        ];

        $this->assertEquals($expected, ArrayUtil::rejectEmptyArrayValues($this->filterArray()));
    }

    public function testIsAssociative(): void
    {
        $this->assertTrue(ArrayUtil::isAssociative(['a' => 'a', 'b' => 'b']));
        $this->assertFalse(ArrayUtil::isAssociative(['a', 'b']));
        $this->assertFalse(ArrayUtil::isAssociative([]));
    }

    public function testKeySortRecursive(): void
    {
        $test = [
            'b' => 'a',
            'a' => [
                'b' => 'b',
                'a' => 'a',
            ],
        ];

        ArrayUtil::ksortRecursive($test);
        $this->assertEquals(['a', 'b'], array_keys($test['a']));
    }

    public function testRemovePrefixesFromKeys(): void
    {
        /** @var array<string, array<string, array<string, mixed>>> $result */
        $result = ArrayUtil::removePrefixFromKeys($this->theArray(), 'camel');

        $this->assertEquals(
            ['_case1', '-case2', 'Case3', ' case-both_works'],
            array_keys($result),
        );

        $this->assertEquals(
            ['_case-both works', '-case1', ' case2'],
            array_keys($result[' case-both_works']),
        );
    }

    public function testRemovePrefixesFromKeysWithoutPrefixes(): void
    {
        $result = ArrayUtil::removePrefixFromKeys(['a' => 'b'], 'camel');
        $this->assertEquals(['a' => 'b'], $result);
    }
}
