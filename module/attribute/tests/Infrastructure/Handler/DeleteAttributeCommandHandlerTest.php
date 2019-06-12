<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Infrastructure\Handler;

use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\Command\DeleteAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\DeleteAttributeCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteAttributeCommandHandlerTest extends TestCase
{
    /**
     * @var DeleteAttributeCommand|MockObject
     */
    private $command;

    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var AbstractAttribute|MockObject
     */
    private $attribute;

    /**
     * @var AttributeFactoryInterface|MockObject
     */
    private $strategy;

    /**
     */
    protected function setUp()
    {
        $this->command = $this->createMock(DeleteAttributeCommand::class);
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(AbstractAttribute::class);
        $this->strategy = $this->createMock(AttributeFactoryInterface::class);
        $this->strategy->method('isSupported')->willReturn(true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAttributeNotFound(): void
    {
        $this->repository->method('load')->willReturn(null);

        $handler = new DeleteAttributeCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }

    /**
     * @expectedException Ergonode\Core\Application\Exception\NotImplementedException
     */
    public function testDelete(): void
    {
        $this->repository->method('load')->willReturn($this->attribute);

        $handler = new DeleteAttributeCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
