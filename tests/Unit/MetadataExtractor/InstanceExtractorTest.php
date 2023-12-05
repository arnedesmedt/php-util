<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit\MetadataExtractor;

use ADS\Util\MetadataExtractor\InstanceExtractor;
use ADS\Util\Tests\Object\Immutable\TestImmutable;
use ADS\Util\Tests\Object\Interface\TestInterface;
use ADS\Util\Tests\Object\ValueObject\String\TestString;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class InstanceExtractorTest extends TestCase
{
    private InstanceExtractor $instanceExtractor;

    protected function setUp(): void
    {
        $this->instanceExtractor = new InstanceExtractor();
    }

    public function testInstanceFromInstanceAndInterface(): void
    {
        $instance = $this->instanceExtractor->instanceFromInstanceAndInterface(
            TestImmutable::fromArray(['test' => 'test']),
            JsonSchemaAwareRecord::class,
        );

        $this->assertInstanceOf(TestImmutable::class, $instance);
    }

    public function testForNonImplementedInterface(): void
    {
        $instance = $this->instanceExtractor->instanceFromInstanceAndInterface(
            TestImmutable::fromArray(['test' => 'test']),
            TestInterface::class,
        );

        $this->assertNull($instance);
    }

    public function testForNonExistingInterface(): void
    {
        $this->expectException(ReflectionException::class);
        $this->instanceExtractor->instanceFromInstanceAndInterface(
            TestImmutable::fromArray(['test' => 'test']),
            TestString::class,
        );
    }
}
