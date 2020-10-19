<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\Entity;

use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UnitTest extends TestCase
{
    /**
     * @var UnitId | MockObject
     */
    private UnitId $id;

    /**
     * @var string $name
     */
    private string $name;

    /**
     * @var string $symbol
     */
    private string $symbol;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(UnitId::class);
        $this->name = 'name';
        $this->symbol = 'symbol';
    }

    /**
     */
    public function testCreateEntity(): void
    {
        $entity = new Unit($this->id, $this->name, $this->symbol);
        $newName = 'new name';
        $newSymbol = 'new symbol';
        self::assertEquals($this->id, $entity->getId());
        self::assertEquals($this->name, $entity->getName());
        self::assertEquals($this->symbol, $entity->getSymbol());

        $entity->changeName($newName);
        self::assertEquals($newName, $entity->getName());

        $entity->changeSymbol($newSymbol);
        self::assertEquals($newSymbol, $entity->getSymbol());
    }
}
