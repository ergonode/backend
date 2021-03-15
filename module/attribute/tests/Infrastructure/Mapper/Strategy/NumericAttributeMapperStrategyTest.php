<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Mapper\Strategy;

use Ergonode\Attribute\Infrastructure\Mapper\Strategy\NumericAttributeMapperStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

class NumericAttributeMapperStrategyTest extends TestCase
{
    public function testSupported(): void
    {
        $type = new AttributeType(NumericAttribute::TYPE);
        $strategy = new NumericAttributeMapperStrategy();
        $this::assertTrue($strategy->supported($type));
    }

    /**
     * @dataProvider getValidData
     */
    public function testValidMapping(array $values, ValueInterface $result): void
    {
        $strategy = new NumericAttributeMapperStrategy();
        $mapped = $strategy->map($values);

        self::assertEquals($result, $mapped);
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testInvalidMapping(array $values): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new NumericAttributeMapperStrategy();
        $strategy->map($values);
    }

    public function getValidData(): array
    {
        return [
            [
                ['pl_PL' => 0],
                new TranslatableStringValue(new TranslatableString(['pl_PL' => 0])),
            ],
            [
                ['pl_PL' => 0.0],
                new TranslatableStringValue(new TranslatableString(['pl_PL' => 0.0])),
            ],
            [
                ['pl_PL' => null],
                new TranslatableStringValue(new TranslatableString(['pl_PL' => null])),
            ],
        ];
    }

    public function getInvalidData(): array
    {
        return [
            [['pl' => 0]],
            [['' => 0]],
            [['' => '']],
            [['pl_PL' => []]],
            [['pl_pl' => str_repeat('a', 257) ]],
        ];
    }
}
