<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit\MetadataExtractor;

use ADS\Util\MetadataExtractor\AttributeExtractor;
use ADS\Util\MetadataExtractor\ClassExtractor;
use ADS\Util\MetadataExtractor\MetadataExtractor;
use ADS\Util\Tests\Object\Attribute\TestAttribute;
use ADS\Util\Tests\Object\Immutable\TestImmutable;
use ADS\Util\Tests\Object\TestObject;
use ADS\Util\Tests\Object\TestObject2;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use LogicException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class MetadataExtractorTest extends TestCase
{
    private MetadataExtractor $metadataExtractor;

    protected function setUp(): void
    {
        $this->metadataExtractor = new MetadataExtractor(
            new AttributeExtractor(),
            new ClassExtractor(),
        );
    }

    public function testHasAttributeOrClassFromReflectionClassWithClasses(): void
    {
        $result = $this->metadataExtractor->hasAttributeOrClassFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [TestObject::class, JsonSchemaAwareRecord::class],
        );

        $this->assertTrue($result);
    }

    public function testHasAttributeOrClassFromReflectionClassWithAttributes(): void
    {
        $result = $this->metadataExtractor->hasAttributeOrClassFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [TestObject::class, TestAttribute::class],
        );

        $this->assertTrue($result);
    }

    public function testHasNoAttributeOrClassFromReflectionClass(): void
    {
        $result = $this->metadataExtractor->hasAttributeOrClassFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [TestObject::class, TestObject2::class],
        );

        $this->assertFalse($result);
    }

    public function testAttributeOrClassFromReflectionClassWithClasses(): void
    {
        $result = $this->metadataExtractor->attributeOrClassFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [TestObject::class, JsonSchemaAwareRecord::class],
        );

        $this->assertEquals(TestImmutable::class, $result);
    }

    public function testAttributeOrClassFromReflectionClassWithAttributes(): void
    {
        $result = $this->metadataExtractor->attributeOrClassFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [TestObject::class, TestAttribute::class],
        );

        $this->assertInstanceOf(TestAttribute::class, $result);
    }

    public function testNeedAttributeOrClassFromReflectionClass(): void
    {
        $result = $this->metadataExtractor->needAttributeOrClassFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [TestObject::class, TestAttribute::class],
        );

        $this->assertInstanceOf(TestAttribute::class, $result);
    }

    public function testNeedAttributeOrClassFromReflectionClassThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->metadataExtractor->needAttributeOrClassFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [TestObject::class, TestObject2::class],
        );
    }

    public function testMetadataFromReflectionClass(): void
    {
        $metadata = $this->metadataExtractor->metadataFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [
                TestObject::class => static fn (TestObject $testObject) => $testObject,
                TestAttribute::class => static fn (TestAttribute $testAttribute) => $testAttribute->test(),
            ],
        );

        $this->assertEquals('test', $metadata);
    }

    public function testNeedMetadataFromReflectionClass(): void
    {
        $metadata = $this->metadataExtractor->needMetadataFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [
                TestObject::class => static fn (TestObject $testObject) => $testObject,
                TestAttribute::class => static fn (TestAttribute $testAttribute) => $testAttribute->test(),
            ],
        );

        $this->assertEquals('test', $metadata);
    }

    public function testNeedMetadataFromReflectionClassThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->metadataExtractor->needMetadataFromReflectionClass(
            new ReflectionClass(TestImmutable::class),
            [
                TestObject::class => static fn (TestObject $testObject) => $testObject,
            ],
        );
    }

    public function testHasAttributeOrInstanceFromInstanceWithClasses(): void
    {
        $result = $this->metadataExtractor->hasAttributeOrInstanceFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [TestObject::class, JsonSchemaAwareRecord::class],
        );

        $this->assertTrue($result);
    }

    public function testHasAttributeOrInstanceFromInstanceWithAttributes(): void
    {
        $result = $this->metadataExtractor->hasAttributeOrInstanceFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [TestObject::class, TestAttribute::class],
        );

        $this->assertTrue($result);
    }

    public function testHasNoAttributeOrInstanceFromInstance(): void
    {
        $result = $this->metadataExtractor->hasAttributeOrInstanceFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [TestObject::class, TestObject2::class],
        );

        $this->assertFalse($result);
    }

    public function testAttributeOrInstanceFromInstanceWithClasses(): void
    {
        $result = $this->metadataExtractor->attributeOrInstanceFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [TestObject::class, JsonSchemaAwareRecord::class],
        );

        $this->assertInstanceOf(TestImmutable::class, $result);
    }

    public function testAttributeOrInstanceFromInstanceWithAttributes(): void
    {
        $result = $this->metadataExtractor->attributeOrInstanceFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [TestObject::class, TestAttribute::class],
        );

        $this->assertInstanceOf(TestAttribute::class, $result);
    }

    public function testNeedAttributeOrInstanceFromInstance(): void
    {
        $result = $this->metadataExtractor->needAttributeOrInstanceFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [TestObject::class, TestAttribute::class],
        );

        $this->assertInstanceOf(TestAttribute::class, $result);
    }

    public function testNeedAttributeOrInstanceFromInstanceThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->metadataExtractor->needAttributeOrInstanceFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [TestObject::class, TestObject2::class],
        );
    }

    public function testMetadataFromInstance(): void
    {
        $metadata = $this->metadataExtractor->metadataFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [
                TestObject::class => static fn (TestObject $testObject) => $testObject,
                TestAttribute::class => static fn (TestAttribute $testAttribute) => $testAttribute->test(),
            ],
        );

        $this->assertEquals('test', $metadata);
    }

    public function testNeedMetadataFromInstance(): void
    {
        $metadata = $this->metadataExtractor->needMetadataFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [
                TestObject::class => static fn (TestObject $testObject) => $testObject,
                TestAttribute::class => static fn (TestAttribute $testAttribute) => $testAttribute->test(),
            ],
        );

        $this->assertEquals('test', $metadata);
    }

    public function testNeedMetadataFromInstanceThrowsException(): void
    {
        $this->expectException(LogicException::class);
        $this->metadataExtractor->needMetadataFromInstance(
            TestImmutable::fromArray(['test' => 'test']),
            [
                TestObject::class => static fn (TestObject $testObject) => $testObject,
            ],
        );
    }
}
