<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
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
        /** @var TranslatableString | MockObject $code */
        $name = $this->createMock(TranslatableString::class);
        /** @var ProductCollectionTypeId | MockObject $code */
        $typeId = $this->createMock(ProductCollectionTypeId::class);

        $event = new ProductCollectionCreatedEvent(
            $id,
            $code,
            $name,
            $typeId,
            true
        );
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($code, $event->getCode());
        $this->assertEquals($name, $event->getName());
        $this->assertEquals($typeId, $event->getTypeId());
        $this->assertTrue($event->isAllVisible());
    }
}
