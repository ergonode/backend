<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Bindings;

use Ergonode\Product\Domain\Command\Bindings\AddProductBindingCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class AddProductBindingCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var AbstractAssociatedProduct|MockObject $product */
        $product = $this->createMock(AbstractAssociatedProduct::class);
        $product->method('getId')->willReturn($this->createMock(ProductId::class));
        /** @var AttributeId|MockObject $bindingId */
        $bindingId = $this->createMock(AttributeId::class);

        $command = new AddProductBindingCommand($product, $bindingId);
        $this->assertSame($product->getId(), $command->getId());
        $this->assertSame($bindingId, $command->getBindingId());
    }
}
