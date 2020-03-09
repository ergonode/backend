<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Handler\Group;

use Ergonode\Attribute\Domain\Command\Group\UpdateAttributeGroupCommand;
use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Group\UpdateAttributeGroupCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateAttributeGroupCommandHandlerTest extends TestCase
{
    /**
     * @var UpdateAttributeGroupCommand|MockObject
     */
    private $command;

    /**
     * @var AttributeGroupRepositoryInterface|MockObject
     */
    private $repository;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(UpdateAttributeGroupCommand::class);
        $this->repository = $this->createMock(AttributeGroupRepositoryInterface::class);
    }

    /**
     */
    public function testUpdate(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn($this->createMock(AttributeGroup::class));
        $this->repository->expects($this->once())->method('save');

        $handler = new UpdateAttributeGroupCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
