<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Infrastructure\Handler;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\AttributeUpdaterInterface;
use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Provider\AttributeUpdaterProvider;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\UpdateAttributeCommandHandler;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateAttributeCommandHandlerTest extends TestCase
{
    /**
     * @var UpdateAttributeCommand|MockObject
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
     * @var AttributeUpdaterProvider|MockObject
     */
    private $provider;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(UpdateAttributeCommand::class);
        $this->command->method('getLabel')->willReturn(new TranslatableString());
        $this->command->method('getPlaceholder')->willReturn(new TranslatableString());
        $this->command->method('getHint')->willReturn(new TranslatableString());
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(AbstractAttribute::class);
        $this->attribute->method('getGroups')->willReturn(new ArrayCollection());
        $this->provider = $this->createMock(AttributeUpdaterProvider::class);
    }

    /**
     */
    public function testAttributeNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->method('load')->willReturn(null);

        $handler = new UpdateAttributeCommandHandler($this->repository, $this->provider);
        $handler->__invoke($this->command);
    }

    /**
     */
    public function testStrategyNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->provider->method('provide')->willThrowException(new \RuntimeException());
        $this->repository->method('load')->willReturn($this->attribute);

        $handler = new UpdateAttributeCommandHandler($this->repository, $this->provider);
        $handler->__invoke($this->command);
    }

    /**
     */
    public function testUpdate(): void
    {
        $this->provider->method('provide')->willReturn($this->createMock(AttributeUpdaterInterface::class));
        $this->repository->method('load')->willReturn($this->attribute);
        $this->repository->expects($this->once())->method('save');

        $handler = new UpdateAttributeCommandHandler($this->repository, $this->provider);
        $handler->__invoke($this->command);
    }
}
