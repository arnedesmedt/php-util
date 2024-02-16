<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit;

use ADS\Util\StringUtil;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StringUtilTest extends TestCase
{
    /** @return array<string, array<string, string>> */
    public static function dataProviderCamelize(): array
    {
        return [
            'one-word' => [
                'input' => 'camel',
                'expected' => 'camel',
            ],
            'snake-case' => [
                'input' => 'snake_case',
                'expected' => 'snakeCase',
            ],
            'camel-case' => [
                'input' => 'camelCase',
                'expected' => 'camelCase',
            ],
            'pascal-case' => [
                'input' => 'PascalCase',
                'expected' => 'pascalCase',
            ],
            'kebab-case' => [
                'input' => 'kebab-case',
                'expected' => 'kebabCase',
                'delimiter' => '-',
            ],
            'space' => [
                'input' => 'space space',
                'expected' => 'spaceSpace',
                'delimiter' => ' ',
            ],
            'combination' => [
                'input' => 'snake_case-kebabCase',
                'expected' => 'snakeCaseKebabCase',
                'delimiter' => '-_',
            ],
        ];
    }

    #[DataProvider('dataProviderCamelize')]
    public function testCamelize(string $input, string $expected, string $delimiter = '_'): void
    {
        $this->assertEquals($expected, StringUtil::camelize($input, $delimiter));
    }

    /** @return array<string, array<string, string>> */
    public static function dataProviderCamelizePascal(): array
    {
        return [
            'one-word' => [
                'input' => 'camel',
                'expected' => 'Camel',
            ],
            'snake-case' => [
                'input' => 'snake_case',
                'expected' => 'SnakeCase',
            ],
            'camel-case' => [
                'input' => 'camelCase',
                'expected' => 'CamelCase',
            ],
            'pascal-case' => [
                'input' => 'PascalCase',
                'expected' => 'PascalCase',
            ],
            'kebab-case' => [
                'input' => 'kebab-case',
                'expected' => 'KebabCase',
                'delimiter' => '-',
            ],
            'space' => [
                'input' => 'space space',
                'expected' => 'SpaceSpace',
                'delimiter' => ' ',
            ],
            'combination' => [
                'input' => 'snake_case-kebabCase',
                'expected' => 'SnakeCaseKebabCase',
                'delimiter' => '-_',
            ],
        ];
    }

    #[DataProvider('dataProviderCamelizePascal')]
    public function testCamelizePascal(string $input, string $expected, string $delimiter = '_'): void
    {
        $this->assertEquals($expected, StringUtil::camelizePascalCase($input, $delimiter));
    }

    public function testEmptyDelimiterWithCamelizePascal(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Delimiters cannot be empty.');

        StringUtil::camelizePascalCase('test', '');
    }

    /** @return array<string, array<string, string>> */
    public static function dataProviderDecamelize(): array
    {
        return [
            'one-word' => [
                'input' => 'camel',
                'expected' => 'camel',
            ],
            'snake-case' => [
                'input' => 'snake_case',
                'expected' => 'snake_case',
            ],
            'double-captial' => [
                'input' => 'snakeCAse',
                'expected' => 'snake_CAse',
            ],
            'special-chars' => [
                'input' => '%$^',
                'expected' => '%$^',
            ],
            'camel-case' => [
                'input' => 'camelCase',
                'expected' => 'camel_case',
            ],
            'pascal-case' => [
                'input' => 'PascalCase',
                'expected' => 'pascal_case',
            ],
            'kebab-case' => [
                'input' => 'kebab-case',
                'expected' => 'kebab_case',
                'bindDelimiter' => '_',
                'splitDelimiters' => '-',
            ],
            'space' => [
                'input' => 'space space',
                'expected' => 'space-space',
                'bindDelimiter' => '-',
                'splitDelimiters' => ' ',
            ],
            'combination' => [
                'input' => 'snake_case-kebabCase',
                'expected' => 'snake_case_kebab_case',
                'bindDelimiter' => '_',
                'splitDelimiters' => '-_',
            ],
        ];
    }

    #[DataProvider('dataProviderDecamelize')]
    public function testDecamelize(
        string $input,
        string $expected,
        string $bindDelimiter = '_',
        string $splitDelimiters = '',
    ): void {
        $this->assertEquals($expected, StringUtil::decamelize($input, $bindDelimiter, $splitDelimiters));
    }

    /** @return array<array<string, mixed>> */
    public static function dataProviderCastFromString(): array
    {
        return [
            'false' => [
                'input' => 'false',
                'expected' => false,
            ],
            'true' => [
                'input' => 'true',
                'expected' => true,
            ],
            'null' => [
                'input' => 'null',
                'expected' => null,
            ],
            '0' => [
                'input' => '0',
                'expected' => 0,
            ],
            '1' => [
                'input' => '1',
                'expected' => 1,
            ],
            '1.2' => [
                'input' => '1.2',
                'expected' => 1.2,
            ],
            'string' => [
                'input' => 'test',
                'expected' => 'test',
            ],
            'json' => [
                'input' => '{"test": "test"}',
                'expected' => ['test' => 'test'],
            ],
        ];
    }

    #[DataProvider('dataProviderCastFromString')]
    public function testCastFromString(string $input, mixed $expected): void
    {
        $this->assertEquals($expected, StringUtil::castFromString($input));
    }

    public function testEntityNamespaceFromClassName(): void
    {
        $this->assertEquals(
            'ADS\Util',
            StringUtil::entityNamespaceFromClassName(StringUtil::class, ['Util', 'Tests']),
        );
        $this->assertEquals(
            'ADS',
            StringUtil::entityNamespaceFromClassName(StringUtil::class, ['ADS']),
        );
    }

    public function testFailedEntityNamespaceFromClassName(): void
    {
        $this->expectExceptionMessageMatches('/^Entity or Model name not found for class/');
        StringUtil::entityNamespaceFromClassName(StringUtil::class);
    }

    public function testEntityNameFromClassName(): void
    {
        $this->assertEquals(
            'StringUtil',
            StringUtil::entityNameFromClassName(StringUtil::class, ['Util']),
        );

        $this->assertEquals(
            'Util',
            StringUtil::entityNameFromClassName(StringUtil::class, ['ADS']),
        );
    }

    public function testFailedEntityNameFromClassName(): void
    {
        $this->expectExceptionMessageMatches('/^Entity or Model name not found for class/');
        StringUtil::entityNameFromClassName(StringUtil::class);
    }
}
