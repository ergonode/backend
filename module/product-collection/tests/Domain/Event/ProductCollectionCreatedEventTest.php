<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionCreatedEvent;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionCreatedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
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
        /** @var \DateTime | MockObject $dateTime */
        $dateTime = $this->createMock(\DateTime::class);

        $event = new ProductCollectionCreatedEvent(
            $id,
            $code,
            $name,
            $description,
            $typeId,
            $dateTime
        );
        self::assertEquals($id, $event->getAggregateId());
        self::assertEquals($code, $event->getCode());
        self::assertEquals($name, $event->getName());
        self::assertEquals($description, $event->getDescription());
        self::assertEquals($typeId, $event->getTypeId());
        self::assertEquals($dateTime, $event->getCreatedAt());
    }
}
