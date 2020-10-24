<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Event\CategoryDeletedEvent;
use PHPUnit\Framework\TestCase;

class CategoryDeletedEventTest extends TestCase
{
    public function testEvent(): void
    {
        $id = $this->createMock(CategoryId::class);

        $command = new CategoryDeletedEvent($id);
        $this->assertEquals($id, $command->getAggregateId());
    }
}
