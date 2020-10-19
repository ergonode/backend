<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Factory;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Factory\ProductCollectionFactory;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionFactoryTest extends TestCase
{
    /**
     */
    public function testFactoryCreation(): void
    {
        /** @var ProductCollectionId | MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        /** @var ProductCollectionCode | MockObject $code */
        $code = $this->createMock(ProductCollectionCode::class);

        /** @var TranslatableString | MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $description */
        $description = $this->createMock(TranslatableString::class);

        /** @var ProductCollectionTypeId | MockObject $typeId */
        $typeId = $this->createMock(ProductCollectionTypeId::class);

        $factory = new ProductCollectionFactory();
        $entity = $factory->create($id, $code, $name, $description, $typeId);

        self::assertEquals($id, $entity->getId());
        self::assertEquals($code, $entity->getCode());
        self::assertEquals($name, $entity->getName());
        self::assertEquals($description, $entity->getDescription());
        self::assertEquals($typeId, $entity->getTypeId());
    }
}
