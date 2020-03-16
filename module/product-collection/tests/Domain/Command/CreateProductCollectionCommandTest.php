<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Faker\Provider\Uuid;

/**
 */
class CreateProductCollectionCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        /** @var ProductCollectionCode | MockObject $code */
        $code = $this->createMock(ProductCollectionCode::class);

        /** @var TranslatableString | MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $description */
        $description = $this->createMock(TranslatableString::class);

        /** @var ProductCollectionTypeId | MockObject $typeId */
        $typeId = $this->createMock(ProductCollectionTypeId::class);

        $command = new CreateProductCollectionCommand($code, $name, $description, $typeId);

        $this->assertEquals($code, $command->getCode());
        $this->assertEquals($name, $command->getName());
        $this->assertEquals($description, $command->getDescription());
        $this->assertEquals($typeId, $command->getTypeId());
    }
}
