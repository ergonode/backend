<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Application\Serializer\Normalizer;

use Ergonode\Attribute\Application\Serializer\Normalizer\AttributeNormalizer;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\ValueObject\SystemAttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
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

    private AbstractAttribute $attribute;

    private AbstractAttribute $systemAttribute;

    protected function setUp(): void
    {
        $this->mockNormalizer = $this->createMock(NormalizerInterface::class);
        $this->mockDenormalizer = $this->createMock(DenormalizerInterface::class);

        $this->normalizer = new AttributeNormalizer();
        $this->normalizer->setNormalizer($this->mockNormalizer);
        $this->normalizer->setDenormalizer($this->mockDenormalizer);
        $id = $this->createMock(AttributeId::class);
        $code = $this->createMock(AttributeCode::class);
        $label = $this->createMock(TranslatableString::class);
        $hint = $this->createMock(TranslatableString::class);
        $placeholder = $this->createMock(TranslatableString::class);
        $scope = $this->createMock(AttributeScope::class);
        $this->attribute = new class($id, $code, $label, $hint, $placeholder, $scope) extends AbstractAttribute {

            public function getType(): string
            {
                return 'TYPE';
            }

            public function isSystem(): bool
            {
                return false;
            }
        };

        $this->systemAttribute = new class($id, $code, $label, $hint, $placeholder, $scope) extends AbstractAttribute {

            public function getType(): string
            {
                return 'TYPE';
            }

            public function isSystem(): bool
            {
                return true;
            }
        };
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

    public function testShouldDenormalizeAttribute(): void
    {
        $data['code'] = 'attribibute_code';
        $type = get_class($this->attribute);
        $this->mockDenormalizer->method('denormalize')->willReturn($this->attribute);

        $result = $this->normalizer->denormalize($data, $type);

        $this->assertTrue($this->normalizer->supportsDenormalization($data, $type));
        $this->assertSame($this->attribute, $result);
        $this->assertSame(AttributeCode::class, get_class($result->getCode()));
        $this->assertSame($data['code'], $result->getCode()->getValue());
    }

    public function testShouldDenormalizeSystemAttribute(): void
    {
        $data['code'] = 'esa_attribibute_code';
        $type = get_class($this->systemAttribute);
        $this->mockDenormalizer->method('denormalize')->willReturn($this->systemAttribute);

        $result = $this->normalizer->denormalize($data, $type);

        $this->assertTrue($this->normalizer->supportsDenormalization($data, $type));
        $this->assertSame($this->systemAttribute, $result);
        $this->assertSame(SystemAttributeCode::class, get_class($result->getCode()));
        $this->assertSame($data['code'], $result->getCode()->getValue());
    }

    public function testShouldNotSupportDenormalizationOfAlreadyCached(): void
    {
        $data['code'] = 'attribibute_code';
        $attribute = $this->createMock(AbstractAttribute::class);
        $type = get_class($attribute);
        $supports = $this->normalizer->supportsDenormalization(
            $data,
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

    public function testShouldThrowExceptionDuringDenormalizationWrongData(): void
    {
        $this->expectException(NotNormalizableValueException::class);
        $this->expectExceptionMessage('Data must be an array');
        $data = 'string';
        $type = get_class($this->attribute);

        $this->normalizer->denormalize($data, $type);
    }

    public function testShouldThrowExceptionDuringDenormalizationWrongCode(): void
    {
        $this->expectException(NotNormalizableValueException::class);
        $this->expectExceptionMessage('Code key must be set and must be string');
        $data['code'] = ['string'];
        $type = get_class($this->attribute);

        $this->normalizer->denormalize($data, $type);
    }

    public function testShouldThrowExceptionDuringDenormalizationWrongCodeFormat(): void
    {
        $this->expectException(NotNormalizableValueException::class);
        $data['code'] = 'attribibute code';
        $type = get_class($this->attribute);
        $this->mockDenormalizer->method('denormalize')->willReturn($this->attribute);

        $this->normalizer->denormalize($data, $type);
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
