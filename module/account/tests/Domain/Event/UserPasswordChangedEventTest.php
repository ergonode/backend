<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\Account\Domain\Event\UserPasswordChangedEvent;
use Ergonode\Account\Domain\ValueObject\Password;
use PHPUnit\Framework\TestCase;

/**
 */
class UserPasswordChangedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        $password = $this->createMock(Password::class);

        $event = new UserPasswordChangedEvent($password);

        $this->assertEquals($password, $event->getPassword());
    }
}
