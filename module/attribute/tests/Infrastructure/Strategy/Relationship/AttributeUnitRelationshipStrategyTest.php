<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Strategy\Relationship;

use Ergonode\Attribute\Infrastructure\Strategy\Relationship\AttributeUnitRelationshipStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Ergonode\SharedKernel\Domain\AggregateId;

class AttributeUnitRelationshipStrategyTest extends TestCase
{
    /**
     * @var AttributeQueryInterface|MockObject
     */
    private AttributeQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(AttributeQueryInterface::class);
    }

    public function testIsSupported(): void
    {
        $validId = $this->createMock(UnitId::class);
        $inValidId = $this->createMock(AggregateId::class);

        $strategy = new AttributeUnitRelationshipStrategy($this->query);

        self::assertTrue($strategy->supports($validId));
        self::assertFalse($strategy->supports($inValidId));
    }

    public function testRelations(): void
    {
        $id = $this->createMock(UnitId::class);
        $relationId = [$this->createMock(AggregateId::class)];

        $this->query->expects(self::once())->method('findAttributeIdsByUnitId')->willReturn($relationId);

        $strategy = new AttributeUnitRelationshipStrategy($this->query);
        $result = $strategy->getRelationshipGroup($id);
        self::assertSame($relationId, $result->getRelations());
    }
}
