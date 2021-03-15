<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Category;

use Ergonode\Product\Domain\Command\Category\RemoveProductCategoryCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RemoveProductCategoryCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        /** @var ProductId|MockObject $product */
        $productId = $this->createMock(ProductId::class);
        /** @var CategoryId|MockObject $bindingId */
        $categoryId = $this->createMock(CategoryId::class);

        $command = new RemoveProductCategoryCommand($productId, $categoryId);
        $this->assertSame($productId, $command->getId());
        $this->assertSame($categoryId, $command->getCategoryId());
    }
}
