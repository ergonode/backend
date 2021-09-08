<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Relations;

use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Command\Relations\RemoveProductChildCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class RemoveProductChildCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        $productId = $this->createMock(ProductId::class);
        $childId = $this->createMock(ProductId::class);

        $command = new RemoveProductChildCommand($productId, $childId);
        self::assertSame($productId, $command->getId());
        self::assertSame($childId, $command->getChildId());
    }
}
