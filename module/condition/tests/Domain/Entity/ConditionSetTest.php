<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Condition\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Domain\Condition\NumericAttributeValueCondition;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ConditionSetTest extends TestCase
{
    /**
     * @var MockObject|ConditionSetId
     */
    private $id;

    /**
     * @var MockObject|ConditionInterface[]
     */
    private $conditions;

    /**
     */
    protected function setUp()
    {
        $this->id = $this->createMock(ConditionSetId::class);
        $this->conditions = $this->createMock(ConditionInterface::class);
    }

    /**
     * @throws \Exception
     */
    public function testConditionSetCreation(): void
    {
        $conditionSet = new ConditionSet($this->id, [$this->conditions]);
        $this->assertSame($this->id, $conditionSet->getId());
        $this->assertSame([$this->conditions], $conditionSet->getConditions());
    }

    /**
     * @throws \Exception
     */
    public function testConditionsChange(): void
    {
        $newConditions = [new NumericAttributeValueCondition($this->createMock(AttributeId::class), 'test', 4)];
        $conditionSet = new ConditionSet($this->id, [$this->conditions]);
        $conditionSet->changeConditions($newConditions);
        $this->assertEquals($newConditions, $conditionSet->getConditions());
    }
}
