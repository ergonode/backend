<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\AttributeImage\Tests\Domain\Updater;

use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\AttributeImage\Domain\Entity\ImageAttribute;
use Ergonode\AttributeImage\Domain\Updater\ImageAttributeUpdater;
use Ergonode\AttributeImage\Domain\ValueObject\ImageFormat;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ImageAttributeUpdaterTest extends TestCase
{

    /**
     * @var UpdateAttributeCommand|MockObject
     */
    private $updateCommand;

    /**
     */
    protected function setUp()
    {
        $this->updateCommand = $this->createMock(UpdateAttributeCommand::class);
        $this->updateCommand->method('getParameter')->willReturn([ImageFormat::JPEG]);
        $this->updateCommand->method('hasParameter')->willReturn('true');
    }

    /**
     */
    public function testIsSupported(): void
    {
        $strategy = new ImageAttributeUpdater();
        $this->assertTrue($strategy->isSupported(new AttributeType(ImageAttribute::TYPE)));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $strategy = new ImageAttributeUpdater();
        $this->assertFalse($strategy->isSupported(new AttributeType('NOT-MATH')));
    }

    /**
     */
    public function testUpdate(): void
    {
        $strategy = new ImageAttributeUpdater();
        /** @var ImageAttribute $attribute */
        $attribute = $this->createMock(ImageAttribute::class);
        $result = $strategy->update($attribute, $this->updateCommand);

        $this->assertSame($attribute, $result);
    }
}
