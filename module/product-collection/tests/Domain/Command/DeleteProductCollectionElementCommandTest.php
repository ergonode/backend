<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Command;

use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\ProductCollection\Domain\Command\DeleteProductCollectionElementCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteProductCollectionElementCommandTest extends TestCase
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

        $command = new DeleteProductCollectionElementCommand($productCollectionId, $productId);

        $this->assertEquals($productCollectionId, $command->getProductCollectionId());
        $this->assertEquals($productId, $command->getProductId());
    }
}
