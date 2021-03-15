<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Command;

use Ergonode\ProductCollection\Domain\Command\DeleteProductCollectionCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteProductCollectionCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        /** @var ProductCollectionId | MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        $command = new DeleteProductCollectionCommand($id);

        $this->assertEquals($id, $command->getId());
    }
}
