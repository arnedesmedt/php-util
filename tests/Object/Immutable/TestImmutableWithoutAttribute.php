<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Object\Immutable;

use ADS\ValueObjects\Implementation\ExamplesLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

class TestImmutableWithoutAttribute implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;
    use ExamplesLogic;

    private readonly string $test; // @phpstan-ignore-line

    public static function __type(): string
    {
        return 'myType';
    }
}
