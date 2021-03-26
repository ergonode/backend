<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Tests\Application\Serializer\Normalizer;

use Ergonode\Completeness\Application\Serializer\Normalizer\CompletenessReadModelNormalizer;
use Ergonode\Completeness\Domain\ReadModel\CompletenessReadModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class CompletenessReadModelNormalizerTest extends TestCase
{
    /**
     * @var AbstractObjectNormalizer|MockObject
     */
    private $mockObjectNormalizer;
    private CompletenessReadModelNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->mockObjectNormalizer = $this->createMock(AbstractObjectNormalizer::class);

        $this->normalizer = new CompletenessReadModelNormalizer(
            $this->mockObjectNormalizer,
        );
    }

    public function testShouldNormalize(): void
    {
        $completeness = $this->createMock(CompletenessReadModel::class);
        $completeness->method('getPercent')->willReturn(75.2);
        $this->mockObjectNormalizer->method('normalize')->willReturn(['normalized' => 'data']);

        $result = $this->normalizer->normalize($completeness);

        $this->assertEquals(
            [
                'normalized' => 'data',
                'percent' => 75.2,
            ],
            $result,
        );
        $this->assertTrue($this->normalizer->supportsNormalization($completeness));
    }

    /**
     * @dataProvider notSupportedNormalizationDataProvider
     *
     * @param mixed $data
     */
    public function testShouldNotSupportNormalization($data): void
    {
        $this->assertFalse($this->normalizer->supportsNormalization($data));
    }

    /**
     * @dataProvider notSupportedNormalizationDataProvider
     *
     * @param mixed $data
     */
    public function testShouldThrowExceptionOnInvalidDataPassed($data): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->normalizer->normalize($data);
    }

    public function notSupportedNormalizationDataProvider(): array
    {
        return [
            [1],
            [1.1],
            ['data'],
            [$this],
        ];
    }
}
