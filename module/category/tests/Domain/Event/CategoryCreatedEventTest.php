<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Event\CategoryCreatedEvent;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryCreatedEventTest extends TestCase
{
    /**
     */
    public function testEvent(): void
    {
        $id = $this->createMock(CategoryId::class);
        $code = $this->createMock(CategoryCode::class);
        $type = 'DEFAULT';
        $name = $this->createMock(TranslatableString::class);

        $event = new CategoryCreatedEvent($id, $code, $type, $name);
        self::assertEquals($id, $event->getAggregateId());
        self::assertEquals($code, $event->getCode());
        self::assertEquals($name, $event->getName());
        self::assertEquals($type, $event->getType());
    }
}
