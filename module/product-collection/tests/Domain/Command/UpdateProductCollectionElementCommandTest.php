<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Command;

use Ergonode\ProductCollection\Domain\Command\UpdateProductCollectionElementCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateProductCollectionElementCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        /** @var ProductCollectionId | MockObject $productCollectionId */
        $productCollectionId = $this->createMock(ProductCollectionId::class);
        /** @var ProductId | MockObject $productId */
        $productId = $this->createMock(ProductId::class);

        $command = new UpdateProductCollectionElementCommand($productCollectionId, $productId, true);

        $this->assertEquals($productCollectionId, $command->getProductCollectionId());
        $this->assertEquals($productId, $command->getProductId());
        $this->assertTrue($command->isVisible());
    }
}
