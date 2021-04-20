<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Handler\Attribute\Update;

use Ergonode\Product\Infrastructure\Handler\Attribute\Update\UpdateProductRelationAttributeCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Command\Attribute\Update\UpdateProductRelationAttributeCommand;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class UpdateProductRelationAttributeCommandHandlerTest extends TestCase
{
    private UpdateProductRelationAttributeCommand $command;
    private AttributeRepositoryInterface $repository;
    private ProductRelationAttribute $attribute;

    protected function setUp(): void
    {
        $this->command = $this->createMock(UpdateProductRelationAttributeCommand::class);
        $this->command->method('getLabel')->willReturn(new TranslatableString());
        $this->command->method('getPlaceholder')->willReturn(new TranslatableString());
        $this->command->method('getHint')->willReturn(new TranslatableString());
        $this->command->method('getGroups')->willReturn([AttributeGroupId::generate()]);
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(ProductRelationAttribute::class);
        $this->attribute->method('getGroups')->willReturn([]);
    }

    public function testAttributeNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->method('load')->willReturn(null);

        $handler = new UpdateProductRelationAttributeCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }

    public function testUpdate(): void
    {
        $this->repository->method('load')->willReturn($this->attribute);
        $this->repository->expects(self::once())->method('save');

        $handler = new UpdateProductRelationAttributeCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
