<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Mapper\Strategy;

use Ergonode\Attribute\Infrastructure\Mapper\Strategy\UnitAttributeMapperStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

class UnitAttributeMapperStrategyTest extends TestCase
{
    public function testSupported(): void
    {
        $type = new AttributeType(UnitAttribute::TYPE);
        $strategy = new UnitAttributeMapperStrategy();
        $this::assertTrue($strategy->supported($type));
    }

    /**
     * @dataProvider getValidData
     */
    public function testValidMapping(array $values, ValueInterface $result): void
    {
        $strategy = new UnitAttributeMapperStrategy();
        $mapped = $strategy->map($values);

        self::assertEquals($result, $mapped);
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testInvalidMapping(array $values): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new UnitAttributeMapperStrategy();
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
