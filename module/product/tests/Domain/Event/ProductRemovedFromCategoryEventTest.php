<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Event;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Product\Domain\Event\ProductRemovedFromCategoryEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductRemovedFromCategoryEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var CategoryCode|MockObject $categoryCode */
        $categoryCode = $this->createMock(CategoryCode::class);
        $event = new ProductRemovedFromCategoryEvent($categoryCode);
        $this->assertEquals($categoryCode, $event->getCategoryCode());
    }
}
