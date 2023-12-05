<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Object\Immutable;

use ADS\ValueObjects\Implementation\ListValue\JsonSchemaAwareCollectionLogic;
use EventEngine\JsonSchema\JsonSchemaAwareCollection;

class TestCollection implements JsonSchemaAwareCollection
{
    use JsonSchemaAwareCollectionLogic;

    public static function itemType(): string
    {
        return TestImmutable::class;
    }
}
