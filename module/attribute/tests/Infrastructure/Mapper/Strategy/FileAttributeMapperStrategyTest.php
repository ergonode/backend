<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Mapper\Strategy;

use Ergonode\Attribute\Infrastructure\Mapper\Strategy\FileAttributeMapperStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\FileAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ramsey\Uuid\Uuid;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;

class FileAttributeMapperStrategyTest extends TestCase
{
    public function testSupported(): void
    {
        $type = new AttributeType(FileAttribute::TYPE);
        $strategy = new FileAttributeMapperStrategy();
        $this::assertTrue($strategy->supported($type));
    }

    /**
     * @dataProvider getValidData
     */
    public function testValidMapping(array $values, ValueInterface $result): void
    {
        $strategy = new FileAttributeMapperStrategy();
        $mapped = $strategy->map($values);

        self::assertEquals($result, $mapped);
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testInvalidMapping(array $values): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new FileAttributeMapperStrategy();
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
            [['pl_PL' => null]],
        ];
    }
}
