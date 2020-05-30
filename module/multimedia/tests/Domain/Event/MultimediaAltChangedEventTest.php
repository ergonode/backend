<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Multimedia\Domain\Event\MultimediaAltChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use PHPUnit\Framework\TestCase;

/**
 */
class MultimediaAltChangedEventTest extends TestCase
{
    /**
     */
    public function testCreationEvent(): void
    {
        $id = $this->createMock(MultimediaId::class);
        $alt = $this->createMock(TranslatableString::class);

        $event = new MultimediaAltChangedEvent($id, $alt);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($alt, $event->getAlt());
    }
}
