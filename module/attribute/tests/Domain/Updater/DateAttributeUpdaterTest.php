<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Updater;

use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\Updater\DateAttributeUpdater;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
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

    /**
     */
    protected function setUp(): void
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
        $this->updateCommand->method('hasParameter')->willReturn(true);
        $strategy = new DateAttributeUpdater();
        /** @var DateAttribute $attribute */
        $attribute = $this->createMock(DateAttribute::class);
        $result = $strategy->update($attribute, $this->updateCommand);

        $this->assertSame($attribute, $result);
    }

    /**
     */
    public function testUpdateWithoutParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $attribute = $this->createMock(DateAttribute::class);
        $strategy = new DateAttributeUpdater();
        $strategy->update($attribute, $this->updateCommand);
    }
}
