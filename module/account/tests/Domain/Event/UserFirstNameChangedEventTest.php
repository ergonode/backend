<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\Account\Domain\Event\User\UserFirstNameChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class UserFirstNameChangedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        $from = 'Old first Name';
        $to = 'New first Name';

        $event = new UserFirstNameChangedEvent($from, $to);

        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
