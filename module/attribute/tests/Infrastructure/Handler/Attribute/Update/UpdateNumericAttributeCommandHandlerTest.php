<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateNumericAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\Update\UpdateNumericAttributeCommandHandler;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class UpdateNumericAttributeCommandHandlerTest extends TestCase
{
    private UpdateNumericAttributeCommand $command;

    private AttributeRepositoryInterface $repository;

    private AbstractAttribute $attribute;

    private ApplicationEventBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->command = $this->createMock(UpdateNumericAttributeCommand::class);
        $this->command->method('getLabel')->willReturn(new TranslatableString());
        $this->command->method('getPlaceholder')->willReturn(new TranslatableString());
        $this->command->method('getHint')->willReturn(new TranslatableString());
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(NumericAttribute::class);
        $this->attribute->method('getGroups')->willReturn([]);
        $this->eventBus = $this->createMock(ApplicationEventBusInterface::class);
    }

    public function testAttributeNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->method('load')->willReturn(null);

        $handler = new UpdateNumericAttributeCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }

    public function testUpdate(): void
    {
        $this->repository->method('load')->willReturn($this->attribute);
        $this->repository->expects($this->once())->method('save');

        $handler = new UpdateNumericAttributeCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }
}
