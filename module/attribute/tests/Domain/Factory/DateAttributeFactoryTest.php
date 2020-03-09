<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Factory;

use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\Factory\DateAttributeFactory;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DateAttributeFactoryTest extends TestCase
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
        $this->createCommand->method('getLabel')->willReturn($this->createMock(TranslatableString::class));
        $this->createCommand->method('getHint')->willReturn($this->createMock(TranslatableString::class));
        $this->createCommand->method('getPlaceholder')->willReturn($this->createMock(TranslatableString::class));
        $this->createCommand->method('getParameter')->willReturn(DateFormat::YYYY_MM_DD);
    }

    /**
     */
    public function testIsSupported(): void
    {
        $strategy = new DateAttributeFactory();
        $this->assertTrue($strategy->supports(new AttributeType(DateAttribute::TYPE)));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $strategy = new DateAttributeFactory();
        $this->assertFalse($strategy->supports(new AttributeType('NOT-MATH')));
    }

    /**
     */
    public function testCreate(): void
    {
        $this->createCommand->method('hasParameter')->willReturn(true);
        $strategy = new DateAttributeFactory();
        $result = $strategy->create($this->createCommand);

        $this->assertInstanceOf(DateAttribute::class, $result);
    }

    /**
     */
    public function testCreateWithoutParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new DateAttributeFactory();
        $strategy->create($this->createCommand);
    }
}
