<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\AttributeDate\Tests\Domain\Factory;

use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\AttributeDate\Domain\Entity\DateAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\AttributeDate\Domain\Updater\DateAttributeUpdater;
use Ergonode\AttributeDate\Domain\ValueObject\DateFormat;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DateAttributeUpdaterTest extends TestCase
{

    /**
     * @var UpdateAttributeCommand|MockObject
     */
    private $updateCommand;

    protected function setUp()
    {
        $this->updateCommand = $this->createMock(UpdateAttributeCommand::class);
        $this->updateCommand->method('getParameter')->willReturn(DateFormat::YYYY_MM_DD);
    }

    /**
     */
    public function testIsSupported(): void
    {
        $strategy = new DateAttributeUpdater();
        $this->assertTrue($strategy->isSupported(new AttributeType(DateAttribute::TYPE)));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $strategy = new DateAttributeUpdater();
        $this->assertFalse($strategy->isSupported(new AttributeType('NOT-MATH')));
    }

    /**
     */
    public function testUpdate(): void
    {
        $this->updateCommand->method('hasParameter')->willReturn('true');
        $strategy = new DateAttributeUpdater();
        /** @var DateAttribute $attribute */
        $attribute = $this->createMock(DateAttribute::class);
        $result = $strategy->update($attribute, $this->updateCommand);

        $this->assertSame($attribute, $result);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUpdateWithoutParameter(): void
    {
        $attribute = $this->createMock(DateAttribute::class);
        $strategy = new DateAttributeUpdater();
        $strategy->update($attribute, $this->updateCommand);
    }
}

