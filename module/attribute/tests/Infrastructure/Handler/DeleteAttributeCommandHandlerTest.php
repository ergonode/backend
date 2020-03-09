<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Infrastructure\Handler;

use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\Command\DeleteAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\DeleteAttributeCommandHandler;
use Ergonode\Core\Infrastructure\Model\RelationshipCollection;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteAttributeCommandHandlerTest extends TestCase
{
    /**
     * @var DeleteAttributeCommand|MockObject
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
     * @var AttributeFactoryInterface|MockObject
     */
    private $strategy;

    /**
     * @var RelationshipsResolver|MockObject
     */
    private $relationshipResolver;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(DeleteAttributeCommand::class);
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(AbstractAttribute::class);
        $this->strategy = $this->createMock(AttributeFactoryInterface::class);
        $this->strategy->method('supports')->willReturn(true);
        $this->relationshipResolver = $this->createMock(RelationshipsResolver::class);
        $this->relationshipResolver->method('resolve')->willReturn(new RelationshipCollection());
    }

    /**
     */
    public function testAttributeNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->method('load')->willReturn(null);

        $handler = new DeleteAttributeCommandHandler($this->repository, $this->relationshipResolver);
        $handler->__invoke($this->command);
    }
}
