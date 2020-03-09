<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Domain\Updater;

use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Updater\InputAttributeUpdater;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class InputAttributeUpdaterTest extends TestCase
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
    }

    /**
     */
    public function testIsSupported(): void
    {
        $updater = new InputAttributeUpdater();
        $this->assertTrue($updater->isSupported(new AttributeType(TextAttribute::TYPE)));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $updater = new InputAttributeUpdater();
        $this->assertFalse($updater->isSupported(new AttributeType('NOT-MATH')));
    }

    /**
     */
    public function testUpdate(): void
    {
        $updater = new InputAttributeUpdater();
        /** @var MultiSelectAttribute $attribute */
        $attribute = $this->createMock(TextAttribute::class);
        $result = $updater->update($attribute, $this->updateCommand);

        $this->assertSame($attribute, $result);
    }
}
