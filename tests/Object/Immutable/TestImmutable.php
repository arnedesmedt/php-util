<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Object\Immutable;

use ADS\Util\Tests\Object\Attribute\TestAttribute;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

#[TestAttribute]
class TestImmutable implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private readonly string $test; // @phpstan-ignore-line

    public static function __type(): string
    {
        return 'myType';
    }

    public function test(): string
    {
        return $this->test;
    }
}
