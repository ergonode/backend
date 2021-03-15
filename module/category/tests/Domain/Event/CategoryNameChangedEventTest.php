<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Event\CategoryNameChangedEvent;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

class CategoryNameChangedEventTest extends TestCase
{
    public function testEvent(): void
    {
        $id = $this->createMock(CategoryId::class);
        $to = $this->createMock(TranslatableString::class);

        $command = new CategoryNameChangedEvent($id, $to);
        $this->assertEquals($id, $command->getAggregateId());
        $this->assertEquals($to, $command->getTo());
    }
}
