<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Event\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCreatedEvent;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryTreeCreatedEventTest extends TestCase
{
    public function testCreateEvent(): void
    {
        /** @var CategoryTreeId|MockObject $id */
        $id = $this->createMock(CategoryTreeId::class);
        /** @var TranslatableString|MockObject $name */
        $name = $this->createMock(TranslatableString::class);
        $code = 'Any tree code';
        $event = new CategoryTreeCreatedEvent($id, $code, $name);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($code, $event->getCode());
        $this->assertEquals($name, $event->getName());
    }
}
