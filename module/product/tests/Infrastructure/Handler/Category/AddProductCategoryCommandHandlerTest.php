<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Handler\Category;

use Ergonode\Product\Domain\Command\Category\AddProductCategoryCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Infrastructure\Handler\Category\AddProductCategoryCommandHandler;
use PHPUnit\Framework\TestCase;

class AddProductCategoryCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testHandling(): void
    {
        $command = $this->createMock(AddProductCategoryCommand::class);
        $product = $this->createMock(AbstractProduct::class);

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())->method('load')->willReturn($product);
        $repository->expects($this->once())->method('save');

        $handler = new AddProductCategoryCommandHandler($repository);
        $handler->__invoke($command);
    }
}
