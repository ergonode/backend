<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Command\UpdateProductCollectionTypeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateProductCollectionTypeCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        /** @var  ProductCollectionTypeId | MockObject $id */
        $id = $this->createMock(ProductCollectionTypeId::class);

        /** @var TranslatableString | MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        $command = new UpdateProductCollectionTypeCommand($id, $name);

        $this->assertEquals($id, $command->getId());
        $this->assertEquals($name, $command->getName());
    }
}
