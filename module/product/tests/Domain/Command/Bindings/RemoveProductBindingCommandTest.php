<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Bindings;

use Ergonode\Product\Domain\Command\Bindings\RemoveProductBindingCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class RemoveProductBindingCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var AbstractAssociatedProduct|MockObject $product */
        $product = $this->createMock(AbstractAssociatedProduct::class);
        $product->method('getId')->willReturn($this->createMock(ProductId::class));
        /** @var SelectAttribute|MockObject $bindingId */
        $binding = $this->createMock(SelectAttribute::class);
        $binding->method('getId')->willReturn($this->createMock(AttributeId::class));

        $command = new RemoveProductBindingCommand($product, $binding);
        $this->assertSame($product->getId(), $command->getId());
        $this->assertSame($binding->getId(), $command->getBindingId());
    }
}
