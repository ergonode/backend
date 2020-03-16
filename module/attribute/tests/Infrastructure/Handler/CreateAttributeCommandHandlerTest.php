<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Infrastructure\Handler;

use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Provider\AttributeFactoryProvider;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\CreateAttributeCommandHandler;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateAttributeCommandHandlerTest extends TestCase
{
    /**
     * @var CreateAttributeCommand|MockObject
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
     * @var AttributeFactoryProvider|MockObject
     */
    private $provider;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(CreateAttributeCommand::class);
        $this->command->method('getLabel')->willReturn(new TranslatableString());
        $this->command->method('getPlaceholder')->willReturn(new TranslatableString());
        $this->command->method('getHint')->willReturn(new TranslatableString());
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(AbstractAttribute::class);
        $this->provider = $this->createMock(AttributeFactoryProvider::class);
    }

    /**
     */
    public function testStrategyNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->provider->method('provide')->willThrowException(new \RuntimeException());
        $this->repository->method('load')->willReturn($this->attribute);

        $handler = new CreateAttributeCommandHandler($this->repository, $this->provider);
        $handler->__invoke($this->command);
    }

    /**
     */
    public function testUpdate(): void
    {
        $this->provider->method('provide')->willReturn($this->createMock(AttributeFactoryInterface::class));
        $this->repository->method('load')->willReturn($this->attribute);
        $this->repository->expects($this->once())->method('save');

        $handler = new CreateAttributeCommandHandler($this->repository, $this->provider);
        $handler->__invoke($this->command);
    }
}
