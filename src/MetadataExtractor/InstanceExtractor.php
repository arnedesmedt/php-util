<?php

declare(strict_types=1);

namespace ADS\Util\MetadataExtractor;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use ReflectionClass;

final class InstanceExtractor
{
    /** @param class-string $interface */
    public function instanceFromInstanceAndInterface(
        JsonSchemaAwareRecord $instance,
        string $interface,
    ): JsonSchemaAwareRecord|null {
        $reflectionClass = new ReflectionClass($instance);
        $implementsInterface = $reflectionClass->implementsInterface($interface);

        if ($implementsInterface) {
            return $instance;
        }

        return null;
    }
}
