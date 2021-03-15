<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command;

use Ergonode\Product\Domain\Command\DeleteProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\TestCase;

class DeleteProductCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testDeleteCommand(): void
    {
        $id = $this->createMock(ProductId::class);
        $command = new DeleteProductCommand($id);

        $this->assertSame($id, $command->getId());
    }
}
