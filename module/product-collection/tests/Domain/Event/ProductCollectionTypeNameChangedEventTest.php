<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeNameChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductCollectionTypeNameChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductCollectionTypeId | MockObject $id */
        $id = $this->createMock(ProductCollectionTypeId::class);

        /** @var TranslatableString | MockObject $to */
        $to = $this->createMock(TranslatableString::class);

        $event = new ProductCollectionTypeNameChangedEvent($id, $to);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($to, $event->getTo());
    }
}
