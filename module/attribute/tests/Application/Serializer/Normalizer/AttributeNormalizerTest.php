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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AttributeNormalizerTest extends TestCase
{
    /**
     * @var NormalizerInterface|MockObject
     */
    private $mockNormalizer;

    private AttributeNormalizer $normalizer;

    private AbstractAttribute $attribute;

    protected function setUp(): void
    {
        $this->mockNormalizer = $this->createMock(NormalizerInterface::class);

        $this->normalizer = new AttributeNormalizer();
        $this->normalizer->setNormalizer($this->mockNormalizer);

        $this->attribute = $this->createMock(AbstractAttribute::class);
        $this->attribute->method('getType')->willReturn('TYPE');
    }

    public function testShouldNormalize(): void
    {
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
