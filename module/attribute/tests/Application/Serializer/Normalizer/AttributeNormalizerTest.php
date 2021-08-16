<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Application\Serializer\Normalizer;

use Ergonode\Attribute\Application\Serializer\Normalizer\AttributeNormalizer;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AttributeNormalizerTest extends TestCase
{
    /**
     * @var NormalizerInterface|MockObject
     */
    private $mockNormalizer;
    /**
     * @var DenormalizerInterface|MockObject
     */
    private $mockDenormalizer;

    private AttributeNormalizer $normalizer;

    private array $data;
    /**
     * @var AbstractAttribute|MockObject
     */
    private $attribute;

    protected function setUp(): void
    {
        $this->mockNormalizer = $this->createMock(NormalizerInterface::class);
        $this->mockDenormalizer = $this->createMock(DenormalizerInterface::class);

        $this->normalizer = new AttributeNormalizer();
        $this->normalizer->setNormalizer($this->mockNormalizer);
        $this->normalizer->setDenormalizer($this->mockDenormalizer);
        $this->data['code'] = 'esa_system_attribute';
        $this->attribute = $this->createMock(AbstractAttribute::class);
    }

    public function testShouldNormalize(): void
    {
        $this->attribute->method('getType')->willReturn('TYPE');
        $this->mockNormalizer->method('normalize')->willReturn(['normalized' => 'data']);

        $result = $this->normalizer->normalize($this->attribute);

        $this->assertEquals(
            [
                'normalized' => 'data',
                'type' => 'TYPE',
            ],
            $result,
        );
        $this->assertTrue($this->normalizer->supportsNormalization($this->attribute));
    }

    public function testShouldNotSupportNormalizationOfAlreadyCached(): void
    {
        $supports = $this->normalizer->supportsNormalization(
            $this->attribute,
            null,
            ['attribute_normalization_'.spl_object_hash($this->attribute) => true],
        );

        $this->assertFalse($supports);
    }

    /**
     * @dataProvider notSupportedDataProvider
     *
     * @param mixed $data
     */
    public function testShouldNotSupportNormalization($data): void
    {
        $this->assertFalse($this->normalizer->supportsNormalization($data));
    }

    public function testShouldDenormalizeAttribute(): void
    {
        $type = get_class($this->attribute);
        $this->attribute->method('isSystem')->willReturn(true);
        $this->mockDenormalizer->method('denormalize')->willReturn($this->attribute);

        $result = $this->normalizer->denormalize($this->data, $type);

        $this->assertTrue($this->normalizer->supportsDenormalization($this->data, $type));
        $this->assertSame($this->attribute, $result);
    }

    public function testShouldNotSupportDenormalizationOfAlreadyCached(): void
    {
        $attribute = $this->createMock(AbstractAttribute::class);
        $type = get_class($attribute);
        $supports = $this->normalizer->supportsDenormalization(
            $this->data,
            $type,
            null,
            ['attribute_denormalization' => true]
        );

        $this->assertFalse($supports);
    }

    public function testShouldNotSupportDenormalization(): void
    {
        $this->assertFalse($this->normalizer->supportsDenormalization([], 'class'));
    }

    /**
     * @return mixed[]
     */
    public function notSupportedDataProvider(): array
    {
        return [
            [1],
            [1.1],
            ['data'],
            [$this],
        ];
    }
}
