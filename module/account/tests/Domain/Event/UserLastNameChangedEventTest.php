<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\Account\Domain\Event\User\UserLastNameChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class UserLastNameChangedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        $from = 'Old last Name';
        $to = 'New last Name';

        $event = new UserLastNameChangedEvent($from, $to);

        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
