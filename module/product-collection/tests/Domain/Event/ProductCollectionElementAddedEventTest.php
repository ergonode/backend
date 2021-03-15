<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Event;

use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementAddedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductCollectionElementAddedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ProductCollectionId | MockObject $id */
        $id = $this->createMock(ProductCollectionId::class);

        /** @var ProductCollectionElement | MockObject $element */
        $element = $this->createMock(ProductCollectionElement::class);
        /** @var \DateTime | MockObject $dateTime */
        $dateTime = $this->createMock(\DateTime::class);

        $event = new ProductCollectionElementAddedEvent($id, $element, $dateTime);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($element, $event->getElement());
        $this->assertEquals($dateTime, $event->getCurrentDateTime());
    }
}
