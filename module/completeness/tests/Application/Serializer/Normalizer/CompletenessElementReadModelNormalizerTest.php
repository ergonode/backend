<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Tests\Application\Serializer\Normalizer;

use Ergonode\Completeness\Application\Serializer\Normalizer\CompletenessElementReadModelNormalizer;
use Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class CompletenessElementReadModelNormalizerTest extends TestCase
{
    private CompletenessElementReadModelNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new CompletenessElementReadModelNormalizer();
    }

    public function testShouldNormalize(): void
    {
        $id = AttributeId::generate();
        $object = new CompletenessElementReadModel(
            $id,
            'name',
            true,
            false,
        );

        $result = $this->normalizer->normalize($object);

        $this->assertEquals(
            [
                'id' => (string) $id,
                'name' => 'name',
            ],
            $result,
        );
        $this->assertTrue($this->normalizer->supportsNormalization($object));
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
