<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Handler\Category;

use Ergonode\Product\Domain\Command\Category\RemoveProductCategoryCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Infrastructure\Handler\Category\RemoveProductCategoryCommandHandler;
use PHPUnit\Framework\TestCase;

class RemoveProductCategoryCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testHandling(): void
    {
        $command = $this->createMock(RemoveProductCategoryCommand::class);
        $product = $this->createMock(AbstractProduct::class);

        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->expects($this->once())->method('load')->willReturn($product);
        $repository->expects($this->once())->method('save');

        $handler = new RemoveProductCategoryCommandHandler($repository);
        $handler->__invoke($command);
    }
}
