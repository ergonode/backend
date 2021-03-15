<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Command;

use Ergonode\ProductCollection\Domain\Command\DeleteProductCollectionTypeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteProductCollectionTypeCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        /** @var ProductCollectionTypeId | MockObject $id */
        $id = $this->createMock(ProductCollectionTypeId::class);

        $command = new DeleteProductCollectionTypeCommand($id);

        $this->assertEquals($id, $command->getId());
    }
}
