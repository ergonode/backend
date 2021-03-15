<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Mapper\Strategy;

use Ergonode\Attribute\Infrastructure\Mapper\Strategy\TextAttributeMapperStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

class TextAttributeMapperStrategyTest extends TestCase
{
    public function testSupported(): void
    {
        $type = new AttributeType(TextAttribute::TYPE);
        $strategy = new TextAttributeMapperStrategy();
        $this::assertTrue($strategy->supported($type));
    }

    /**
     * @dataProvider getValidData
     */
    public function testValidMapping(array $values, ValueInterface $result): void
    {
        $strategy = new TextAttributeMapperStrategy();
        $mapped = $strategy->map($values);

        self::assertEquals($result, $mapped);
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testInvalidMapping(array $values): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new TextAttributeMapperStrategy();
        $strategy->map($values);
    }

    public function getValidData(): array
    {
        return [
            [
                ['pl_PL' => 'string'],
                new TranslatableStringValue(new TranslatableString(['pl_PL' => 'string'])),
            ],
            [
                ['pl_PL' => null],
                new TranslatableStringValue(new TranslatableString(['pl_PL' => null])),
            ],
            [
                ['pl_PL' => str_repeat('a', 255)],
                new TranslatableStringValue(new TranslatableString(['pl_PL' => str_repeat('a', 255)])),
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
            [['pl_PL' => []]],
            [['pl_pl' => str_repeat('a', 256) ]],
        ];
    }
}
