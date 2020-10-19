<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Factory;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Factory\ProductCollectionTypeFactory;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionTypeFactoryTest extends TestCase
{
    /**
     */
    public function testEventCreation()
    {
        /** @var ProductCollectionTypeId | MockObject $id */
        $id = $this->createMock(ProductCollectionTypeId::class);
        /** @var ProductCollectionTypeCode | MockObject $code */
        $code = $this->createMock(ProductCollectionTypeCode::class);
        /** @var TranslatableString | MockObject $name */
        $name = $this->createMock(TranslatableString::class);
        $factory = new ProductCollectionTypeFactory();
        $entity = $factory->create($id, $code, $name);

        self::assertEquals($id, $entity->getId());
        self::assertEquals($code, $entity->getCode());
        self::assertEquals($name, $entity->getName());
    }
}
