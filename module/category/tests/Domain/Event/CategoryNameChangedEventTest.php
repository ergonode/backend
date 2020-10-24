<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
        $from = $this->createMock(TranslatableString::class);
        $to = $this->createMock(TranslatableString::class);

        $command = new CategoryNameChangedEvent($id, $from, $to);
        $this->assertEquals($id, $command->getAggregateId());
        $this->assertEquals($from, $command->getFrom());
        $this->assertEquals($to, $command->getTo());
    }
}
