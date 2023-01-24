<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit;

use ADS\Util\StringUtil;
use PHPUnit\Framework\TestCase;

class StringUtilTest extends TestCase
{
    public function testCamelize(): void
    {
        $this->assertEquals('camel', StringUtil::camelize('camel'));
        $this->assertEquals('camelCase', StringUtil::camelize('camel_case'));
        $this->assertEquals('camelCase', StringUtil::camelize('camelCase'));
        $this->assertEquals('pascalCase', StringUtil::camelize('PascalCase'));
        $this->assertEquals('camelCase', StringUtil::camelize('camel-case', '-'));
        $this->assertEquals('camelCaseBoth', StringUtil::camelize('camel-case_both', '-_'));
        $this->assertEquals('camelCase', StringUtil::camelize('camel case', ' '));
        $this->assertEquals('PascalCase', StringUtil::camelizePascalCase('pascal_case', '_'));
        $this->assertEquals('PascalCase', StringUtil::camelizePascalCase('pascalCase', '_'));
        $this->assertEquals('PascalCase', StringUtil::camelizePascalCase('pascal-case', '-'));
        $this->assertEquals('PascalCase', StringUtil::camelizePascalCase('pascal case', ' '));
    }

    public function testDecamelize(): void
    {
        $this->assertEquals('camel_case', StringUtil::decamelize('camelCase'));
        $this->assertEquals('pascal_case', StringUtil::decamelize('Pascal-Case', splitDelimiters: '-'));
        $this->assertEquals('camel case', StringUtil::decamelize('camelCase', ' '));
        $this->assertEquals('word', StringUtil::decamelize('word'));
        $this->assertEquals('camel_CAse', StringUtil::decamelize('camelCAse'));
        $this->assertEquals('^&*', StringUtil::decamelize('^&*'));
    }

    public function testCastFromString(): void
    {
        $this->assertFalse(StringUtil::castFromString('false'));
        $this->assertTrue(StringUtil::castFromString('true'));
        $this->assertNull(StringUtil::castFromString('null'));
        $this->assertIsFloat(StringUtil::castFromString('2.3'));
        $this->assertEquals(2.3, StringUtil::castFromString('2.3'));
        $this->assertIsInt(StringUtil::castFromString('2'));
        $this->assertEquals(2, StringUtil::castFromString('2'));
        $this->assertEquals('test', StringUtil::castFromString('test'));
        $this->assertIsArray(StringUtil::castFromString('{"test": "test"}'));
        $this->assertArrayHasKey('test', StringUtil::castFromString('{"test": "test"}'));
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
