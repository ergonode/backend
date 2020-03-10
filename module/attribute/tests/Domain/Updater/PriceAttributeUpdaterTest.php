<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Domain\Updater;

use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Updater\PriceAttributeUpdater;

/**
 */
class PriceAttributeUpdaterTest extends TestCase
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
        $this->updateCommand->method('getParameter')->willReturn('PLN');
    }

    /**
     */
    public function testIsSupported(): void
    {
        $strategy = new PriceAttributeUpdater();
        $this->assertTrue($strategy->isSupported(new AttributeType(PriceAttribute::TYPE)));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $strategy = new PriceAttributeUpdater();
        $this->assertFalse($strategy->isSupported(new AttributeType('NOT-MATH')));
    }

    /**
     */
    public function testUpdate(): void
    {
        $this->updateCommand->method('hasParameter')->willReturn(true);
        $strategy = new PriceAttributeUpdater();
        /** @var PriceAttribute $attribute */
        $attribute = $this->createMock(PriceAttribute::class);
        $result = $strategy->update($attribute, $this->updateCommand);

        $this->assertSame($attribute, $result);
    }

    /**
     */
    public function testUpdateWithoutParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $attribute = $this->createMock(PriceAttribute::class);
        $strategy = new PriceAttributeUpdater();
        $strategy->update($attribute, $this->updateCommand);
    }
}
