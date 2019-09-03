<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Event;

use Ergonode\Attribute\Domain\Entity\AttributeGroupId;
use Ergonode\Attribute\Domain\Event\AttributeGroupDeletedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeGroupDeletedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var AttributeGroupId $groupId */
        $groupId = $this->createMock(AttributeGroupId::class);
        $event = new AttributeGroupDeletedEvent($groupId);
        $this->assertEquals($groupId, $event->getGroupId());
    }
}
