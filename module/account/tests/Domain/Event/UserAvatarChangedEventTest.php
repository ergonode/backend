<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\Account\Domain\Event\User\UserAvatarChangedEvent;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use PHPUnit\Framework\TestCase;

/**
 */
class UserAvatarChangedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        $multimediaId = $this->createMock(MultimediaId::class);

        $event = new UserAvatarChangedEvent($multimediaId);

        $this->assertEquals($multimediaId, $event->getAvatarId());
    }
}
