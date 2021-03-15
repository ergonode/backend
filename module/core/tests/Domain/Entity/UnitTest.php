<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\Entity;

use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    /**
     * @var UnitId | MockObject
     */
    private UnitId $id;

    private string $name;

    private string $symbol;

    protected function setUp(): void
    {
        $this->id = $this->createMock(UnitId::class);
        $this->name = 'name';
        $this->symbol = 'symbol';
    }

    public function testCreateEntity(): void
    {
        $entity = new Unit($this->id, $this->name, $this->symbol);
        $newName = 'new name';
        $newSymbol = 'new symbol';
        $this->assertEquals($this->id, $entity->getId());
        $this->assertEquals($this->name, $entity->getName());
        $this->assertEquals($this->symbol, $entity->getSymbol());

        $entity->changeName($newName);
        $this->assertEquals($newName, $entity->getName());

        $entity->changeSymbol($newSymbol);
        $this->assertEquals($newSymbol, $entity->getSymbol());
    }
}
