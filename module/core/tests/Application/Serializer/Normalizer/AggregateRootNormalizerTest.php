<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Serializer\Normalizer;

use Ergonode\Core\Application\Serializer\Normalizer\AggregateRootNormalizer;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class AggregateRootNormalizerTest extends TestCase
{
    /**
     * @var AbstractObjectNormalizer|MockObject
     */
    private $mockNormalizer;
    private AggregateRootNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->mockNormalizer = $this->createMock(AbstractObjectNormalizer::class);

        $this->normalizer = new AggregateRootNormalizer(
            $this->mockNormalizer,
        );
    }

    public function testShouldNormalize(): void
    {
        $root = $this->createMock(AbstractAggregateRoot::class);
        $this->mockNormalizer->method('normalize')->willReturn([
            'property' => 'val',
            'sequence' => 3,
            'events' => [],
        ]);

        $this->assertTrue($this->normalizer->supportsNormalization($root));

        $result = $this->normalizer->normalize($root);

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
        $root = $this->createMock(AbstractAggregateRoot::class);
        $this->mockNormalizer->method('normalize')->willReturn([
            'property' => 'val',
        ]);

        $this->assertTrue($this->normalizer->supportsNormalization($root));

        $result = $this->normalizer->normalize($root);

        $this->assertEquals(
            [
                'property' => 'val',
            ],
            $result,
        );
    }

    public function testShouldDenormalize(): void
    {
        $root = $this->createMock(AbstractAggregateRoot::class);
        $this->mockNormalizer->method('denormalize')->willReturn($root);

        $result = $this->normalizer->denormalize(
            [
                'events' => [],
                'sequence' => 5,
                'property' => 'val',
            ],
            get_class($root),
        );

        $this->assertTrue($this->normalizer->supportsDenormalization([], get_class($root)));
        $this->assertSame($root, $result);
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
            [AbstractAggregateRoot::class],
        ];
    }

    public function testShouldThrowExceptionOnNonArray(): void
    {
        $this->expectException(NotNormalizableValueException::class);

        $this->normalizer->denormalize('string', AbstractAggregateRoot::class);
    }
}
