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
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class AggregateRootNormalizerTest extends TestCase
{
    /**
     * @var ContextAwareNormalizerInterface|MockObject
     */
    private $mockNormalizer;
    private AggregateRootNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->mockNormalizer = $this->createMock(ContextAwareNormalizerInterface::class);

        $this->normalizer = new AggregateRootNormalizer();
        $this->normalizer->setNormalizer($this->mockNormalizer);
    }

    public function testShouldNormalizer(): void
    {
        $root = $this->createMock(AbstractAggregateRoot::class);
        $this->mockNormalizer->method('normalize')->willReturn([
            'sequence' => 3,
            'events' => [],
        ]);

        $this->assertTrue($this->normalizer->supportsNormalization($root));

        $result = $this->normalizer->normalize($root);

        $this->assertEquals(
            [
                'sequence' => 3,
            ],
            $result,
        );
    }

    public function testShouldNotSupportAlreadySetInContext(): void
    {
        $root = $this->createMock(AbstractAggregateRoot::class);

        $this->assertFalse(
            $this->normalizer->supportsNormalization(
                $root,
                null,
                ['aggregate_root_normalization_'.spl_object_hash($root) => true],
            ),
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

    public function testShouldNormalizeWithoutEvents(): void
    {
        $root = $this->createMock(AbstractAggregateRoot::class);
        $this->mockNormalizer->method('normalize')->willReturn([
            'sequence' => 3,
        ]);

        $this->assertTrue($this->normalizer->supportsNormalization($root));

        $result = $this->normalizer->normalize($root);

        $this->assertEquals(
            [
                'sequence' => 3,
            ],
            $result,
        );
    }
}
