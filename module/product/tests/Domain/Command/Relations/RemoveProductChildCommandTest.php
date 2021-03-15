<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Relations;

use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Command\Relations\RemoveProductChildCommand;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class RemoveProductChildCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var AbstractAssociatedProduct|MockObject $product */
        $product = $this->createMock(AbstractAssociatedProduct::class);
        $product->method('getId')->willReturn($this->createMock(ProductId::class));
        /** @var AbstractProduct|MockObject $child */
        $child = $this->createMock(AbstractAssociatedProduct::class);
        $child->method('getId')->willReturn($this->createMock(ProductId::class));

        $command = new RemoveProductChildCommand($product, $child);
        $this->assertSame($product->getId(), $command->getId());
        $this->assertSame($child->getId(), $command->getChildId());
    }
}
