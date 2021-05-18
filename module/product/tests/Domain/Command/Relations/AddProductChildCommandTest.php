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

class AddProductChildCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        $productId = $this->createMock(ProductId::class);
        $childId = $this->createMock(ProductId::class);

        $command = new AddProductChildCommand($productId, $childId);
        self::assertSame($productId, $command->getId());
        self::assertSame($childId, $command->getChildId());
    }
}
