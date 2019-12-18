<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Product\Domain\Event\ProductVersionIncreasedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductVersionIncreasedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        $from = 1;
        $to = 2;
        $event = new ProductVersionIncreasedEvent($from, $to);
        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
