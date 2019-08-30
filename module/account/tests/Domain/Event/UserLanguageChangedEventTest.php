<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\Account\Domain\Event\User\UserLanguageChangedEvent;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;

/**
 */
class UserLanguageChangedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        $from = $this->createMock(Language::class);
        $to = $this->createMock(Language::class);

        $event = new UserLanguageChangedEvent($from, $to);

        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
