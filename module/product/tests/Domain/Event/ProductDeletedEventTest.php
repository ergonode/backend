<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Event\ProductDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductDeletedEventTest extends TestCase
{
    public function testeEventCreation(): void
    {
        /** @var ProductId | MockObject $id */
        $id = $this->createMock(ProductId::class);

        $event = new ProductDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
