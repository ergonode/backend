<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Relations;

use Ergonode\Product\Domain\Command\Relations\AddProductChildCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use PHPUnit\Framework\MockObject\MockObject;

class AddProductChildCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var AbstractAssociatedProduct|MockObject $product */
        $product = $this->createMock(AbstractAssociatedProduct::class);
        $product->method('getId')->willReturn($this->createMock(ProductId::class));
        /** @var ProductId|MockObject $childId */
        $childId = $this->createMock(ProductId::class);

        $command = new AddProductChildCommand($product, $childId);
        $this->assertSame($product->getId(), $command->getId());
        $this->assertSame($childId, $command->getChildId());
    }
}
