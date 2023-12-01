<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit;

use ADS\Util\ScalarUtil;
use ADS\Util\Tests\Unit\ValueObject\List\TestImmutable;
use ADS\Util\Tests\Unit\ValueObject\String\TestString;
use PHPUnit\Framework\TestCase;

class ScalarUtilTest extends TestCase
{
    public function testToScalar(): void
    {
        $immutable = TestImmutable::fromArray(['test' => 'test']);
        $valueObject = TestString::fromString('string');
        $scalar = true;

        $this->assertEquals(['test' => 'test'], ScalarUtil::toScalar($immutable));
        $this->assertEquals('string', ScalarUtil::toScalar($valueObject));
        $this->assertTrue(ScalarUtil::toScalar($scalar));
    }

    public function testRecursiveToScalar(): void
    {
        $immutable = TestImmutable::fromArray(['test' => 'test']);
        $valueObject = TestString::fromString('string');

        $array = [
            'test' => 'test',
            'immutable' => $immutable,
            'nested' => ['valueObject' => $valueObject],
            'nestedImmutable' => ['immutable' => $immutable],
        ];

        $this->assertEquals(
            [
                'test' => 'test',
                'immutable' => ['test' => 'test'],
                'nested' => ['valueObject' => 'string'],
                'nestedImmutable' => [
                    'immutable' => ['test' => 'test'],
                ],
            ],
            ScalarUtil::toScalar($array),
        );
    }
}
