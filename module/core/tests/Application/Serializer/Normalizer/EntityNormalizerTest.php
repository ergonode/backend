<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Serializer\Normalizer;

use Ergonode\Core\Application\Serializer\Normalizer\EntityNormalizer;
use Ergonode\EventSourcing\Domain\AbstractEntity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class EntityNormalizerTest extends TestCase
{
    /**
     * @var AbstractObjectNormalizer|MockObject
     */
    private $mockNormalizer;
    private EntityNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->mockNormalizer = $this->createMock(AbstractObjectNormalizer::class);

        $this->normalizer = new EntityNormalizer(
            $this->mockNormalizer,
        );
    }

    public function testShouldNormalize(): void
    {
        $entity = $this->createMock(AbstractEntity::class);
        $this->mockNormalizer->method('normalize')->willReturn([
            'property' => 'val',
            'aggregateRoot' => 'root',
        ]);

        $this->assertTrue($this->normalizer->supportsNormalization($entity));

        $result = $this->normalizer->normalize($entity);

        $this->assertEquals(
            [
                'property' => 'val',
            ],
            $result,
        );
    }

    /**
     * @dataProvider notSupportedNormalizationDataProvider
     *
     * @param mixed $data
     */
    public function testShouldNotSupport($data): void
    {
        $this->assertFalse($this->normalizer->supportsNormalization($data));
    }

    public function notSupportedNormalizationDataProvider(): array
    {
        return [
            ['string'],
            [1],
            [[]],
            [$this],
        ];
    }

    public function testShouldThrowExceptionOnInvalidObject(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->normalizer->normalize($this);
    }

    public function testShouldNormalizeWithoutEventsAndSequence(): void
    {
        $entity = $this->createMock(AbstractEntity::class);
        $this->mockNormalizer->method('normalize')->willReturn([
            'property' => 'val',
        ]);

        $this->assertTrue($this->normalizer->supportsNormalization($entity));

        $result = $this->normalizer->normalize($entity);

        $this->assertEquals(
            [
                'property' => 'val',
            ],
            $result,
        );
    }

    public function testShouldDenormalize(): void
    {
        $entity = $this->createMock(AbstractEntity::class);
        $this->mockNormalizer->method('denormalize')->willReturn($entity);

        $result = $this->normalizer->denormalize(
            [
                'aggregateRoot' => 'root',
                'property' => 'val',
            ],
            get_class($entity),
        );

        $this->assertTrue($this->normalizer->supportsDenormalization([], get_class($entity)));
        $this->assertSame($entity, $result);
    }

    /**
     * @dataProvider notSupportedDenormalizationDataProvider
     */
    public function testShouldNotSupportDenormalization(string $type): void
    {
        $this->assertFalse($this->normalizer->supportsNormalization($type));
    }

    public function notSupportedDenormalizationDataProvider(): array
    {
        return [
            ['string'],
            [\stdClass::class],
            [AbstractEntity::class],
        ];
    }

    public function testShouldThrowExceptionOnNonArray(): void
    {
        $this->expectException(NotNormalizableValueException::class);

        $this->normalizer->denormalize('string', AbstractEntity::class);
    }
}
