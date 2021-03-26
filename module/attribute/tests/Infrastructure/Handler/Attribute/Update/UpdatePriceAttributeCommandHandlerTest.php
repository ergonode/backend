<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdatePriceAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\Update\UpdatePriceAttributeCommandHandler;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Money\Currency;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class UpdatePriceAttributeCommandHandlerTest extends TestCase
{
    private UpdatePriceAttributeCommand $command;

    private AttributeRepositoryInterface $repository;

    private AbstractAttribute $attribute;

    private ApplicationEventBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->command = $this->createMock(UpdatePriceAttributeCommand::class);
        $this->command->method('getLabel')->willReturn(new TranslatableString());
        $this->command->method('getPlaceholder')->willReturn(new TranslatableString());
        $this->command->method('getHint')->willReturn(new TranslatableString());
        $this->command->method('getCurrency')->willReturn(new Currency('PLN'));
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(PriceAttribute::class);
        $this->attribute->method('getGroups')->willReturn([]);
        $this->eventBus = $this->createMock(ApplicationEventBusInterface::class);
    }

    public function testAttributeNotFound(): void
    {
        $this->expectException(\LogicException::class);
        $this->repository->method('load')->willReturn(null);

        $handler = new UpdatePriceAttributeCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }

    public function testUpdate(): void
    {
        $this->repository->method('load')->willReturn($this->attribute);
        $this->repository->expects($this->once())->method('save');

        $handler = new UpdatePriceAttributeCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }
}
