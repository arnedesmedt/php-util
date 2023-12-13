<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit;

use ADS\Util\ScalarUtil;
use ADS\Util\Tests\Object\Immutable\TestImmutable;
use PHPUnit\Framework\TestCase;

class ScalarUtilTest extends TestCase
{
    public function testToScalar(): void
    {
        $immutable = TestImmutable::fromArray(['test' => 'test']);
        $scalar = true;

        $this->assertEquals(['test' => 'test'], ScalarUtil::toScalar($immutable));
        $this->assertTrue(ScalarUtil::toScalar($scalar));
    }

    public function testRecursiveToScalar(): void
    {
        $immutable = TestImmutable::fromArray(['test' => 'test']);

        $array = [
            'test' => 'test',
            'immutable' => $immutable,
            'nestedImmutable' => ['immutable' => $immutable],
        ];

        $this->assertEquals(
            [
                'test' => 'test',
                'immutable' => ['test' => 'test'],
                'nestedImmutable' => [
                    'immutable' => ['test' => 'test'],
                ],
            ],
            ScalarUtil::toScalar($array),
        );
    }
}
