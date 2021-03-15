<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductCollectionTypeDeletedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductCollectionTypeId | MockObject $id */
        $id = $this->createMock(ProductCollectionTypeId::class);

        $event = new ProductCollectionTypeDeletedEvent($id);

        $this->assertEqualsCanonicalizing($id, $event->getAggregateId());
    }
}
