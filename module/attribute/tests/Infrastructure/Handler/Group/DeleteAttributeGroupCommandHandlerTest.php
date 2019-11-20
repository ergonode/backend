<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Handler\Group;

use Ergonode\Attribute\Domain\Command\Group\DeleteAttributeGroupCommand;
use Ergonode\Attribute\Domain\Command\Group\UpdateAttributeGroupCommand;
use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Group\DeleteAttributeGroupCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteAttributeGroupCommandHandlerTest extends TestCase
{
    /**
     * @var DeleteAttributeGroupCommand|MockObject
     */
    private $command;

    /**
     * @var AttributeGroupRepositoryInterface|MockObject
     */
    private $repository;

    /**
     */
    protected function setUp()
    {
        $this->command = $this->createMock(DeleteAttributeGroupCommand::class);
        $this->repository = $this->createMock(AttributeGroupRepositoryInterface::class);
    }

    /**
     */
    public function testUpdate(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn($this->createMock(AttributeGroup::class));
        $this->repository->expects($this->once())->method('delete');

        $handler = new DeleteAttributeGroupCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
