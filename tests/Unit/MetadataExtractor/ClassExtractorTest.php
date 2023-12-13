<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit\MetadataExtractor;

use ADS\Util\MetadataExtractor\ClassExtractor;
use ADS\Util\Tests\Object\Immutable\TestImmutable;
use ADS\Util\Tests\Object\Interface\TestInterface;
use ADS\Util\Tests\Object\TestObject;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class ClassExtractorTest extends TestCase
{
    private ClassExtractor $classExtractor;

    protected function setUp(): void
    {
        $this->classExtractor = new ClassExtractor();
    }

    public function testClassFromClassAndInterface(): void
    {
        $class = $this->classExtractor->classFromClassAndInterface(
            TestImmutable::class,
            JsonSchemaAwareRecord::class,
        );

        $this->assertEquals(TestImmutable::class, $class);
    }

    public function testClassFromReflectionClassAndInterface(): void
    {
        $class = $this->classExtractor->classFromReflectionClassAndInterface(
            new ReflectionClass(TestImmutable::class),
            JsonSchemaAwareRecord::class,
        );

        $this->assertEquals(TestImmutable::class, $class);
    }

    public function testForNonImplementedInterface(): void
    {
        $class = $this->classExtractor->classFromClassAndInterface(
            TestImmutable::class,
            TestInterface::class,
        );

        $this->assertNull($class);
    }

    public function testForNonExistingClass(): void
    {
        $this->expectException(ReflectionException::class);
        $this->classExtractor->classFromClassAndInterface(
            'test', //@phpstan-ignore-line
            TestInterface::class,
        );
    }

    public function testForNonExistingInterface(): void
    {
        $this->expectException(ReflectionException::class);
        $this->classExtractor->classFromClassAndInterface(
            TestImmutable::class,
            TestObject::class,
        );
    }
}
