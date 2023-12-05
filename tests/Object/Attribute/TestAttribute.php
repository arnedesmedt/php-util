<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Object\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class TestAttribute
{
    public function test(): string
    {
        return 'test';
    }
}
