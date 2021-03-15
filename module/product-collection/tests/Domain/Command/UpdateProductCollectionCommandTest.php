<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Command\UpdateProductCollectionCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateProductCollectionCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        /** @var ProductCollectionId | MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        /** @var TranslatableString | MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $description */
        $description = $this->createMock(TranslatableString::class);

        /** @var ProductCollectionTypeId | MockObject $typeId */
        $typeId = $this->createMock(ProductCollectionTypeId::class);

        $command = new UpdateProductCollectionCommand($id, $name, $description, $typeId);

        $this->assertEquals($id, $command->getId());
        $this->assertEquals($name, $command->getName());
        $this->assertEquals($description, $command->getDescription());
        $this->assertEquals($typeId, $command->getTypeId());
    }
}
