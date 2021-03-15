<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Domain\Event;

use Ergonode\Multimedia\Domain\Event\MultimediaNameChangedEvent;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class MultimediaNameChangedEventTest extends TestCase
{
    public function testCreationEvent(): void
    {
        $id = $this->createMock(MultimediaId::class);
        $name = 'any name';

        $event = new MultimediaNameChangedEvent($id, $name);

        self::assertSame($id, $event->getAggregateId());
        self::assertSame($name, $event->getName());
    }
}
