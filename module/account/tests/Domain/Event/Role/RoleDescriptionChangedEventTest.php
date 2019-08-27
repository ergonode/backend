<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event\Role;

use Ergonode\Account\Domain\Event\Role\RoleDescriptionChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class RoleDescriptionChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        $from = 'Old Description';
        $to = 'New Description';

        $event = new RoleDescriptionChangedEvent($from, $to);
        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
