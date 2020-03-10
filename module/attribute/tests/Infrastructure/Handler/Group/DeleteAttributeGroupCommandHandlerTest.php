<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Handler\Group;

use Ergonode\Attribute\Domain\Command\Group\DeleteAttributeGroupCommand;
use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Group\DeleteAttributeGroupCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteAttributeGroupCommandHandlerTest extends TestCase
{
    /**
     * @var DeleteAttributeGroupCommand|MockObject
     */
    private $command;

    /**
     * @var AttributeGroupRepositoryInterface|MockObject
     */
    private $groupRepository;

    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private $attributeRepository;

    /**
     * @var AttributeGroupQueryInterface|MockObject
     */
    private $query;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(DeleteAttributeGroupCommand::class);
        $this->groupRepository = $this->createMock(AttributeGroupRepositoryInterface::class);
        $this->attributeRepository = $this->createMock(AttributeRepositoryInterface::class);
        $this->query = $this->createMock(AttributeGroupQueryInterface::class);
    }

    /**
     */
    public function testUpdate(): void
    {
        $this->groupRepository
            ->expects(
                $this->once()
            )
            ->method('load')->willReturn($this->createMock(AttributeGroup::class));
        $this->groupRepository->expects($this->once())->method('delete');

        $handler = new DeleteAttributeGroupCommandHandler(
            $this->groupRepository,
            $this->attributeRepository,
            $this->query
        );
        $handler->__invoke($this->command);
    }
}
