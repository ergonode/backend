<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Handler\Group;

use Ergonode\Attribute\Domain\Command\Group\CreateAttributeGroupCommand;
use Ergonode\Attribute\Domain\Factory\Group\AttributeGroupFactory;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Group\CreateAttributeGroupCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateAttributeGroupCommandHandlerTest extends TestCase
{
    /**
     * @var CreateAttributeGroupCommand|MockObject
     */
    private $command;

    /**
     * @var AttributeGroupRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var AttributeGroupFactory|MockObject
     */
    private $factory;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(CreateAttributeGroupCommand::class);
        $this->repository = $this->createMock(AttributeGroupRepositoryInterface::class);
        $this->factory = $this->createMock(AttributeGroupFactory::class);
    }

    /**
     */
    public function testUpdate(): void
    {
        $this->factory->expects($this->once())->method('create');
        $this->repository->expects($this->once())->method('save');

        $handler = new CreateAttributeGroupCommandHandler($this->factory, $this->repository);
        $handler->__invoke($this->command);
    }
}
