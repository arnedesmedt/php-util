<?php

declare(strict_types=1);

namespace ADS\Util\MetadataExtractor;

use ReflectionClass;

final class ClassExtractor
{
    /**
     * @param ReflectionClass<T> $reflectionClass
     * @param class-string<T>    $interface
     *
     * @return class-string<T>|null
     *
     * @template T of object
     */
    public function classFromReflectionClassAndInterface(
        ReflectionClass $reflectionClass,
        string $interface,
    ): string|null {
        $implementsInterface = $reflectionClass->implementsInterface($interface);

        if ($implementsInterface) {
            return $reflectionClass->getName();
        }

        return null;
    }

    /**
     * @param class-string<T>|T $class
     * @param class-string<T>   $interface
     *
     * @return class-string<T>|null
     *
     * @template T of object
     */
    public function classFromClassAndInterface(
        mixed $class,
        string $interface,
    ): string|null {
        return $this->classFromReflectionClassAndInterface(
            new ReflectionClass($class),
            $interface,
        );
    }
}
