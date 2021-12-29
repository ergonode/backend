<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Mapper\Strategy;

use Ergonode\Product\Infrastructure\Mapper\Strategy\ProductRelationAttributeMapperStrategy;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ramsey\Uuid\Uuid;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;

class ProductRelationAttributeMapperStrategyTest extends TestCase
{
    public function testSupported(): void
    {
        $type = new AttributeType(ProductRelationAttribute::TYPE);
        $strategy = new ProductRelationAttributeMapperStrategy();
        $this::assertTrue($strategy->supported($type));
    }

    /**
     * @dataProvider getValidData
     */
    public function testValidMapping(array $values, ValueInterface $result): void
    {
        $aggregateId = $this->createMock(AggregateId::class);
        $strategy = new ProductRelationAttributeMapperStrategy();
        $mapped = $strategy->map($values, $aggregateId);

        self::assertEquals($result, $mapped);
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testInvalidMapping(array $values): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new ProductRelationAttributeMapperStrategy();
        $strategy->map($values);
    }

    public function getValidData(): array
    {
        $uuid1 = Uuid::uuid4()->toString();
        $uuid2 = Uuid::uuid4()->toString();

        return [
            [
                ['pl_PL' => [$uuid1]],
                new StringCollectionValue(['pl_PL' => $uuid1]),
            ],
            [
                ['pl_PL' => [$uuid2]],
                new StringCollectionValue(['pl_PL' => $uuid2]),
            ],
            [
                ['pl_PL' => [$uuid1, $uuid2]],
                new StringCollectionValue(['pl_PL' => $uuid1.','.$uuid2]),
            ],
            [
                ['pl_PL' => []],
                new StringCollectionValue(['pl_PL' => null]),
            ],
            [
                ['pl_PL' => null],
                new StringCollectionValue(['pl_PL' => null]),
            ],
            [
                ['pl_PL' => $uuid1],
                new StringCollectionValue(['pl_PL' => $uuid1]),
            ],
            [
                ['pl_PL' => $uuid1.','.$uuid2],
                new StringCollectionValue(['pl_PL' => $uuid1.','.$uuid2]),
            ],
        ];
    }

    public function getInvalidData(): array
    {
        return [
            [['pl' => 'string']],
            [['' => 'string']],
            [['' => '']],
            [['pl_PL' => 0.0]],
            [['pl_PL' => 0]],
        ];
    }
}
