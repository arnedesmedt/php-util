<?php

declare(strict_types=1);

namespace ADS\Util\MetadataExtractor;

use ReflectionAttribute;
use ReflectionClass;

use function reset;

final class AttributeExtractor
{
    /**
     * @param ReflectionClass<object> $reflectionClass
     * @param class-string<T>         $attributeClass
     *
     * @return T|null
     *
     * @template T of object
     */
    public function attributeInstanceFromReflectionClassAndAttribute(
        ReflectionClass $reflectionClass,
        string $attributeClass,
    ): object|null {
        return $this->attributeFromReflectionClassAndAttribute($reflectionClass, $attributeClass)?->newInstance();
    }

    /**
     * @param ReflectionClass<object> $reflectionClass
     * @param class-string<T>         $attributeClass
     *
     * @return ReflectionAttribute<T>|null
     *
     * @template T of object
     */
    public function attributeFromReflectionClassAndAttribute(
        ReflectionClass $reflectionClass,
        string $attributeClass,
    ): ReflectionAttribute|null {
        $attributes = $reflectionClass->getAttributes($attributeClass, ReflectionAttribute::IS_INSTANCEOF);

        if (empty($attributes)) {
            return null;
        }

        return reset($attributes);
    }

    /**
     * @param class-string<object>|object $class
     * @param class-string<T>             $attributeClass
     *
     * @return T|null
     *
     * @template T of object
     */
    public function attributeInstanceFromClassAndAttribute(
        mixed $class,
        string $attributeClass,
    ): object|null {
        return $this->attributeInstanceFromReflectionClassAndAttribute(
            new ReflectionClass($class),
            $attributeClass,
        );
    }

    /**
     * @param class-string<T> $attributeClass
     *
     * @return T|null
     *
     * @template T of object
     */
    public function attributeInstanceFromInstanceAndAttribute(
        object $record,
        string $attributeClass,
    ): object|null {
        return $this->attributeInstanceFromReflectionClassAndAttribute(
            new ReflectionClass($record),
            $attributeClass,
        );
    }
}
