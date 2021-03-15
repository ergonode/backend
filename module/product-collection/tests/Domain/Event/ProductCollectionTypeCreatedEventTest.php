<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeCreatedEvent;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductCollectionTypeCreatedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductCollectionTypeId | MockObject $id */
        $id = $this->createMock(ProductCollectionTypeId::class);

        /** @var ProductCollectionTypeCode | MockObject $code */
        $code = $this->createMock(ProductCollectionTypeCode::class);

        /** @var TranslatableString | MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        $event = new ProductCollectionTypeCreatedEvent($id, $code, $name);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($code, $event->getCode());
        $this->assertEquals($name, $event->getName());
    }
}
