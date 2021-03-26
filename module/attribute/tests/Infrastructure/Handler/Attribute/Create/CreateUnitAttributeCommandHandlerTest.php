<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Handler\Attribute\Create;

use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateUnitAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\Create\CreateUnitAttributeCommandHandler;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class CreateUnitAttributeCommandHandlerTest extends TestCase
{
    private CreateUnitAttributeCommand $command;

    private AttributeRepositoryInterface $repository;

    private AbstractAttribute $attribute;

    private ApplicationEventBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->command = $this->createMock(CreateUnitAttributeCommand::class);
        $this->command->method('getLabel')->willReturn(new TranslatableString());
        $this->command->method('getPlaceholder')->willReturn(new TranslatableString());
        $this->command->method('getHint')->willReturn(new TranslatableString());
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(AbstractAttribute::class);
        $this->eventBus = $this->createMock(ApplicationEventBusInterface::class);
    }

    public function testHandleCommand(): void
    {
        $this->repository->method('load')->willReturn($this->attribute);
        $this->repository->expects($this->once())->method('save');

        $handler = new CreateUnitAttributeCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }
}
