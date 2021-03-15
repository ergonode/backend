<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Command;

use Ergonode\ProductCollection\Domain\Command\AddProductCollectionElementsCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\TestCase;

class AddProductCollectionElementsCommandTest extends TestCase
{
    public function testCommand(): void
    {
        $productCollectionId = $this->createMock(ProductCollectionId::class);
        $productId = $this->createMock(ProductId::class);

        $command = new AddProductCollectionElementsCommand($productCollectionId, [$productId]);

        $this->assertEquals($productCollectionId, $command->getProductCollectionId());
        $this->assertEquals([$productId], $command->getProductIds());
    }
}
