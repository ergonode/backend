<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MultimediaCreatedEventTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testEventCreate(): void
    {
        /** @var MultimediaId | MockObject $multimediaId */
        $multimediaId = $this->createMock(MultimediaId::class);
        $name = 'name';
        $ext = 'extension';
        $size = 123;
        $hash = $this->createMock(Hash::class);
        $mime = 'text/json';
        $event = new MultimediaCreatedEvent(
            $multimediaId,
            $name,
            $ext,
            $size,
            $hash,
            $mime
        );

        $this->assertEquals($multimediaId, $event->getAggregateId());
        $this->assertEquals($name, $event->getName());
        $this->assertEquals($ext, $event->getExtension());
        $this->assertEquals($size, $event->getSize());
        $this->assertEquals($mime, $event->getMime());
        $this->assertEquals($hash, $event->getHash());
    }
}
