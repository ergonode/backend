<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Relations;

use Ergonode\Product\Domain\Command\Relations\AddProductChildrenCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\TestCase;

class AddProductChildrenCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        $productId = $this->createMock(ProductId::class);
        $childId = $this->createMock(ProductId::class);

        $command = new AddProductChildrenCommand($productId, [$childId]);
        self::assertSame($productId, $command->getId());
        self::assertSame([$childId], $command->getChildren());
    }
}
