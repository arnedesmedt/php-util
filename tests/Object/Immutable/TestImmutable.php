<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Object\Immutable;

use ADS\Util\Tests\Object\Attribute\TestAttribute;
use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\ExamplesLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

#[TestAttribute]
class TestImmutable implements JsonSchemaAwareRecord, HasExamples
{
    use JsonSchemaAwareRecordLogic;
    use ExamplesLogic;

    private readonly string $test; // @phpstan-ignore-line

    public static function __type(): string
    {
        return 'myType';
    }

    public function test(): string
    {
        return $this->test;
    }

    public static function example(): self
    {
        return self::fromArray(['test' => 'test']);
    }
}