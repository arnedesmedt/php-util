<?php

declare(strict_types=1);

namespace ADS\Util\Tests\Unit\MetadataExtractor;

use ADS\Util\MetadataExtractor\AttributeExtractor;
use ADS\Util\Tests\Object\Attribute\TestAttribute;
use ADS\Util\Tests\Object\Immutable\TestImmutable;
use ADS\Util\Tests\Object\Immutable\TestImmutableWithoutAttribute;
use ADS\Util\Tests\Object\ValueObject\String\TestString;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class AttributeExtractorTest extends TestCase
{
    private AttributeExtractor $attibuteExtractor;

    protected function setUp(): void
    {
        $this->attibuteExtractor = new AttributeExtractor();
    }

    public function testAttributeInstanceFromClassAndAttribute(): void
    {
        $attribute = $this->attibuteExtractor->attributeInstanceFromClassAndAttribute(
            TestImmutable::class,
            TestAttribute::class,
        );

        $this->assertInstanceOf(TestAttribute::class, $attribute);
    }

    public function testAttributeInstanceFromInstanceAndAttribute(): void
    {
        $attribute = $this->attibuteExtractor->attributeInstanceFromInstanceAndAttribute(
            TestImmutable::fromArray(['test' => 'test']),
            TestAttribute::class,
        );

        $this->assertInstanceOf(TestAttribute::class, $attribute);
    }

    public function testAttributeInstanceFromReflectionClassAndAttribute(): void
    {
        $attribute = $this->attibuteExtractor->attributeInstanceFromReflectionClassAndAttribute(
            new ReflectionClass(TestImmutable::class),
            TestAttribute::class,
        );

        $this->assertInstanceOf(TestAttribute::class, $attribute);
    }

    public function testForNonExistingAttribute(): void
    {
        $attribute = $this->attibuteExtractor->attributeInstanceFromClassAndAttribute(
            TestImmutable::class,
            TestString::class,
        );

        $this->assertNull($attribute);
    }

    public function testForClassWithoutAttribute(): void
    {
        $attribute = $this->attibuteExtractor->attributeInstanceFromClassAndAttribute(
            TestImmutableWithoutAttribute::class,
            TestString::class,
        );

        $this->assertNull($attribute);
    }
}
