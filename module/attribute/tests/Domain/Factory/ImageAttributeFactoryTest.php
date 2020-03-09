<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Domain\Factory;

use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Attribute\Domain\Factory\ImageAttributeFactory;
use Ergonode\Multimedia\Domain\ValueObject\ImageFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ImageAttributeFactoryTest extends TestCase
{
    /**
     * @var CreateAttributeCommand|MockObject
     */
    private $createCommand;

    /**
     */
    protected function setUp(): void
    {
        $this->createCommand = $this->createMock(CreateAttributeCommand::class);
        $this->createCommand->method('hasParameter')->willReturn(true);
        $this->createCommand->method('getParameter')->willReturn([ImageFormat::JPG]);
        $this->createCommand->method('getLabel')->willReturn($this->createMock(TranslatableString::class));
        $this->createCommand->method('getHint')->willReturn($this->createMock(TranslatableString::class));
        $this->createCommand->method('getPlaceholder')->willReturn($this->createMock(TranslatableString::class));
    }

    /**
     */
    public function testIsSupported(): void
    {
        $strategy = new ImageAttributeFactory();
        $this->assertTrue($strategy->supports(new AttributeType(ImageAttribute::TYPE)));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $strategy = new ImageAttributeFactory();
        $this->assertFalse($strategy->supports(new AttributeType('NOT-MATH')));
    }

    /**
     */
    public function testCreate(): void
    {
        $strategy = new ImageAttributeFactory();
        $result = $strategy->create($this->createCommand);

        $this->assertInstanceOf(ImageAttribute::class, $result);
    }
}
