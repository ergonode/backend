<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Condition\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Condition\NumericAttributeValueCondition;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
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
     * @var MockObject|ConditionSetCode
     */
    private $code;

    /**
     * @var MockObject|TranslatableString
     */
    private $name;

    /**
     * @var MockObject|TranslatableString
     */
    private $description;

    /**
     * @var MockObject|ConditionInterface[]
     */
    private $conditions;

    protected function setUp()
    {
        $this->id = $this->createMock(ConditionSetId::class);
        $this->code = $this->createMock(ConditionSetCode::class);
        $this->name = $this->createMock(TranslatableString::class);
        $this->description = $this->createMock(TranslatableString::class);
        $this->conditions = $this->createMock(ConditionInterface::class);
    }

    /**
     */
    public function testConditionSetCreation(): void
    {
        $conditionSet = new ConditionSet($this->id, $this->code, $this->name, $this->description, [$this->conditions]);
        $this->assertSame($this->id, $conditionSet->getId());
        $this->assertSame($this->code, $conditionSet->getCode());
        $this->assertSame($this->name, $conditionSet->getName());
        $this->assertSame($this->description, $conditionSet->getDescription());
        $this->assertSame([$this->conditions], $conditionSet->getConditions());
    }

    /**
     */
    public function testNameChange(): void
    {
        $newName = new TranslatableString(['EN' => 'name']);
        $conditionSet = new ConditionSet($this->id, $this->code, $this->name, $this->description, [$this->conditions]);
        $conditionSet->changeName($newName);
        $this->assertSame($newName, $conditionSet->getName());
    }

    /**
     */
    public function testDescriptionChange(): void
    {
        $newDescription = new TranslatableString(['EN' => 'description']);
        $conditionSet = new ConditionSet($this->id, $this->code, $this->name, $this->description, [$this->conditions]);
        $conditionSet->changeDescription($newDescription);
        $this->assertSame($newDescription, $conditionSet->getDescription());
    }

    /**
     */
    public function testConditionsChange(): void
    {
        $newConditions = [new NumericAttributeValueCondition($this->createMock(AttributeId::class), 'test', 4)];
        $conditionSet = new ConditionSet($this->id, $this->code, $this->name, $this->description, [$this->conditions]);
        $conditionSet->changeConditions($newConditions);
        $this->assertEquals($newConditions, $conditionSet->getConditions());
    }
}
