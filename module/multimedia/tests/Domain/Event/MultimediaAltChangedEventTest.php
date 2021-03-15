<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Domain\Event;

use Ergonode\Multimedia\Domain\Event\MultimediaAltChangedEvent;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class MultimediaAltChangedEventTest extends TestCase
{
    public function testCreationEvent(): void
    {
        $id = $this->createMock(MultimediaId::class);
        $alt = $this->createMock(TranslatableString::class);

        $event = new MultimediaAltChangedEvent($id, $alt);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($alt, $event->getAlt());
    }
}
