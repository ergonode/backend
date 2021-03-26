<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Handler;

use Ergonode\Attribute\Domain\Command\DeleteAttributeCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\DeleteAttributeCommandHandler;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolver;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class DeleteAttributeCommandHandlerTest extends TestCase
{
    private DeleteAttributeCommand $command;

    private AttributeRepositoryInterface $repository;

    private RelationshipsResolver $relationshipResolver;

    private ApplicationEventBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->command = $this->createMock(DeleteAttributeCommand::class);
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->relationshipResolver = $this->createMock(RelationshipsResolver::class);
        $this->eventBus = $this->createMock(ApplicationEventBusInterface::class);
    }

    public function testAttributeNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->method('load')->willReturn(null);

        $handler = new DeleteAttributeCommandHandler($this->repository, $this->relationshipResolver, $this->eventBus);
        $handler->__invoke($this->command);
    }
}
