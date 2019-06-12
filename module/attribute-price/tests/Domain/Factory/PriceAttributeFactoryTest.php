<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\AttributePrice\Tests\Domain\Factory;

use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\AttributePrice\Domain\Entity\PriceAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\AttributePrice\Domain\Factory\PriceAttributeFactory;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class PriceAttributeFactoryTest extends TestCase
{
    /**
     * @var CreateAttributeCommand|MockObject
     */
    private $createCommand;

    protected function setUp()
    {
        $this->createCommand = $this->createMock(CreateAttributeCommand::class);
        $this->createCommand->method('getLabel')->willReturn($this->createMock(TranslatableString::class));
        $this->createCommand->method('getHint')->willReturn($this->createMock(TranslatableString::class));
        $this->createCommand->method('getPlaceholder')->willReturn($this->createMock(TranslatableString::class));
        $this->createCommand->method('getParameter')->willReturn('PLN');
    }

    /**
     */
    public function testIsSupported(): void
    {
        $strategy = new PriceAttributeFactory();
        $this->assertTrue($strategy->isSupported(new AttributeType(PriceAttribute::TYPE)));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $strategy = new PriceAttributeFactory();
        $this->assertFalse($strategy->isSupported(new AttributeType('NOT-MATH')));
    }

    /**
     */
    public function testCreate(): void
    {
        $this->createCommand->method('hasParameter')->willReturn('true');
        $strategy = new PriceAttributeFactory();
        $result = $strategy->create($this->createCommand);

        $this->assertInstanceOf(PriceAttribute::class, $result);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithoutParameter(): void
    {
        $strategy = new PriceAttributeFactory();
        $strategy->create($this->createCommand);
    }
}
