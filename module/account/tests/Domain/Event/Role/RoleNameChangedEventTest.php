<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event\Role;

use Ergonode\Account\Domain\Event\Role\RoleNameChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class RoleNameChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        $from = 'Old Description';
        $to = 'New Description';

        $event = new RoleNameChangedEvent($from, $to);
        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
