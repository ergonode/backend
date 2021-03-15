<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Mapper\Strategy;

use Ergonode\Attribute\Infrastructure\Mapper\Strategy\SelectAttributeMapperStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ramsey\Uuid\Uuid;

class SelectAttributeMapperStrategyTest extends TestCase
{
    public function testSupported(): void
    {
        $type = new AttributeType(SelectAttribute::TYPE);
        $strategy = new SelectAttributeMapperStrategy();
        $this::assertTrue($strategy->supported($type));
    }

    /**
     * @dataProvider getValidData
     */
    public function testValidMapping(array $values, ValueInterface $result): void
    {
        $strategy = new SelectAttributeMapperStrategy();
        $mapped = $strategy->map($values);

        self::assertEquals($result, $mapped);
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testInvalidMapping(array $values): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new SelectAttributeMapperStrategy();
        $strategy->map($values);
    }

    public function getValidData(): array
    {
        $uuid1 = Uuid::uuid4()->toString();
        $uuid2 = Uuid::uuid4()->toString();

        return [
            [
                ['pl_PL' => $uuid1],
                new TranslatableStringValue(new TranslatableString(['pl_PL' => $uuid1])),
            ],
            [
                ['pl_PL' => $uuid2],
                new TranslatableStringValue(new TranslatableString(['pl_PL' => $uuid2])),
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
            [['pl' => 'string']],
            [['' => 'string']],
            [['' => '']],
            [['pl_PL' => 0.0]],
            [['pl_PL' => 0]],
            [['pl_PL' => []]],
            [['pl_pl' => str_repeat('a', 257)]],
        ];
    }
}
