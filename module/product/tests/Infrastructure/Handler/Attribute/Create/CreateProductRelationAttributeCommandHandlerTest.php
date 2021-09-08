<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Handler\Attribute\Create;

use Ergonode\Product\Infrastructure\Handler\Attribute\Create\CreateProductRelationAttributeCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Command\Attribute\Create\CreateProductRelationAttributeCommand;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class CreateProductRelationAttributeCommandHandlerTest extends TestCase
{
    private CreateProductRelationAttributeCommand $command;
    private AttributeRepositoryInterface $repository;
    private ProductRelationAttribute $attribute;

    protected function setUp(): void
    {
        $this->command = $this->createMock(CreateProductRelationAttributeCommand::class);
        $this->command->method('getLabel')->willReturn(new TranslatableString());
        $this->command->method('getPlaceholder')->willReturn(new TranslatableString());
        $this->command->method('getHint')->willReturn(new TranslatableString());
        $this->command->method('getGroups')->willReturn([AttributeGroupId::generate()]);
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(ProductRelationAttribute::class);
    }

    public function testHandleCommand(): void
    {
        $this->repository->method('load')->willReturn($this->attribute);
        $this->repository->expects(self::once())->method('save');

        $handler = new CreateProductRelationAttributeCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
