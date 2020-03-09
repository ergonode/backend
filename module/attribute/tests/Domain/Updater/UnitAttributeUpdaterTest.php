<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Domain\Updater;

use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\Updater\UnitAttributeUpdater;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UnitAttributeUpdaterTest extends TestCase
{
    /**
     * @var UpdateAttributeCommand|MockObject
     */
    private $updateCommand;

    /**
     */
    protected function setUp(): void
    {
        $this->updateCommand = $this->createMock(UpdateAttributeCommand::class);
        $this->updateCommand->method('getParameter')->willReturn('UNIT');
    }

    /**
     */
    public function testIsSupported(): void
    {
        $strategy = new UnitAttributeUpdater();
        $this->assertTrue($strategy->isSupported(new AttributeType(UnitAttribute::TYPE)));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $strategy = new UnitAttributeUpdater();
        $this->assertFalse($strategy->isSupported(new AttributeType('NOT-MATH')));
    }

    /**
     */
    public function testUpdate(): void
    {
        $this->updateCommand->method('hasParameter')->willReturn(true);
        $strategy = new UnitAttributeUpdater();
        /** @var UnitAttribute $attribute */
        $attribute = $this->createMock(UnitAttribute::class);
        $result = $strategy->update($attribute, $this->updateCommand);

        $this->assertSame($attribute, $result);
    }

    /**
     */
    public function testUpdateWithoutParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $attribute = $this->createMock(UnitAttribute::class);
        $strategy = new UnitAttributeUpdater();
        $strategy->update($attribute, $this->updateCommand);
    }
}
