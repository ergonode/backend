<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Event\User\UserLanguageChangedEvent;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UserLanguageChangedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $from = $this->createMock(Language::class);
        $to = $this->createMock(Language::class);

        $event = new UserLanguageChangedEvent($id, $from, $to);

        self::assertEquals($id, $event->getAggregateId());
        self::assertEquals($from, $event->getFrom());
        self::assertEquals($to, $event->getTo());
    }
}
