<?php

declare(strict_types=1);

namespace ADS\Util\MetadataExtractor;

use Closure;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use LogicException;
use ReflectionClass;
use ReflectionException;

final class MetadataExtractor
{
    public const METADATA_NOT_FOUND = '---no-metadata-found---';

    public function __construct(
        private readonly AttributeExtractor $attributeExtractor,
        private readonly ClassExtractor $classExtractor,
    ) {
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     * @param array<class-string>     $attributesOrClasses
     */
    public function hasAttributeOrClassFromReflectionClass(
        ReflectionClass $reflectionClass,
        array $attributesOrClasses,
    ): bool {
        $attributeOrClass = $this->attributeOrClassFromReflectionClass(
            $reflectionClass,
            $attributesOrClasses,
        );

        return $attributeOrClass !== null;
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     * @param array<class-string>     $attributesOrClasses
     */
    public function attributeOrClassFromReflectionClass(
        ReflectionClass $reflectionClass,
        array $attributesOrClasses,
    ): mixed {
        foreach ($attributesOrClasses as $attributeOrClass) {
            $attributeOrClass = $this->attributeOrClass($reflectionClass, $attributeOrClass);

            if ($attributeOrClass !== null) {
                return $attributeOrClass;
            }
        }

        return null;
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     * @param array<class-string>     $attributesOrClasses
     */
    public function needAttributeOrClassFromReflectionClass(
        ReflectionClass $reflectionClass,
        array $attributesOrClasses,
    ): mixed {
        $attributeOrClass = $this->attributeOrClassFromReflectionClass(
            $reflectionClass,
            $attributesOrClasses,
        );

        if ($attributeOrClass === null) {
            throw new LogicException('No attribute or class found');
        }

        return $attributeOrClass;
    }

    /**
     * @param ReflectionClass<object>      $reflectionClass
     * @param array<class-string, Closure> $attributesOrClasses
     */
    public function metadataFromReflectionClass(ReflectionClass $reflectionClass, array $attributesOrClasses): mixed
    {
        foreach ($attributesOrClasses as $attributeOrClass => $closure) {
            $attributeOrClass = $this->attributeOrClass($reflectionClass, $attributeOrClass);

            if ($attributeOrClass !== null) {
                return $closure($attributeOrClass);
            }
        }

        return self::METADATA_NOT_FOUND;
    }

    /**
     * @param ReflectionClass<object>      $reflectionClass
     * @param array<class-string, Closure> $attributesOrClasses
     */
    public function needMetadataFromReflectionClass(ReflectionClass $reflectionClass, array $attributesOrClasses): mixed
    {
        $metadata = $this->metadataFromReflectionClass($reflectionClass, $attributesOrClasses);

        if ($metadata === self::METADATA_NOT_FOUND) {
            throw new LogicException('No metadata found');
        }

        return $metadata;
    }

    /** @param array<class-string> $attributesOrClasses */
    public function hasAttributeOrInstanceFromInstance(JsonSchemaAwareRecord $record, array $attributesOrClasses): bool
    {
        $attributeOrInstance = $this->attributeOrInstanceFromInstance($record, $attributesOrClasses);

        return $attributeOrInstance !== null;
    }

    /** @param array<class-string> $attributesOrClasses */
    public function attributeOrInstanceFromInstance(JsonSchemaAwareRecord $record, array $attributesOrClasses): mixed
    {
        foreach ($attributesOrClasses as $attributeOrClass) {
            $attributeOrInstance = $this->attributeOrInstance($record, $attributeOrClass);

            if ($attributeOrInstance !== null) {
                return $attributeOrInstance;
            }
        }

        return null;
    }

    /** @param array<class-string> $attributesOrClasses */
    public function needAttributeOrInstanceFromInstance(
        JsonSchemaAwareRecord $record,
        array $attributesOrClasses,
    ): mixed {
        $attributeOrInstance = $this->attributeOrInstanceFromInstance(
            $record,
            $attributesOrClasses,
        );

        if ($attributeOrInstance === null) {
            throw new LogicException('No attribute or instance found');
        }

        return $attributeOrInstance;
    }

    /** @param array<class-string, Closure> $attributesOrClasses */
    public function metadataFromInstance(JsonSchemaAwareRecord $record, array $attributesOrClasses): mixed
    {
        foreach ($attributesOrClasses as $attributeOrClass => $closure) {
            $attributeOrInstance = $this->attributeOrInstance($record, $attributeOrClass);

            if ($attributeOrInstance !== null) {
                return $closure($attributeOrInstance, $record);
            }
        }

        return self::METADATA_NOT_FOUND;
    }

    /** @param array<class-string, Closure> $attributesOrClasses */
    public function needMetadataFromInstance(JsonSchemaAwareRecord $record, array $attributesOrClasses): mixed
    {
        $metadata = $this->metadataFromInstance($record, $attributesOrClasses);

        if ($metadata === self::METADATA_NOT_FOUND) {
            throw new LogicException('No metadata found');
        }

        return $metadata;
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     * @param class-string            $attributeOrClass
     *
     * @return object|class-string|null
     */
    private function attributeOrClass(
        ReflectionClass $reflectionClass,
        string $attributeOrClass,
    ): string|object|null {
        try {
            $attribute = $this->attributeExtractor->attributeInstanceFromReflectionClassAndAttribute(
                $reflectionClass,
                $attributeOrClass,
            );
        } catch (ReflectionException) {
            $attribute = null;
        }

        try {
            $class = $this->classExtractor->classFromReflectionClassAndInterface(
                $reflectionClass,
                $attributeOrClass,
            );
        } catch (ReflectionException) {
            $class = null;
        }

        return $attribute ?? $class;
    }

    /** @param class-string $attributeOrClass */
    private function attributeOrInstance(
        JsonSchemaAwareRecord $record,
        string $attributeOrClass,
    ): object|null {
        try {
            $attribute = $this->attributeExtractor->attributeInstanceFromInstanceAndAttribute(
                $record,
                $attributeOrClass,
            );
        } catch (ReflectionException) {
            $attribute = null;
        }

        return $attribute ?? ($record instanceof $attributeOrClass ? $record : null);
    }
}
