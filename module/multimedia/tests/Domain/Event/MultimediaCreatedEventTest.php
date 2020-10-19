<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
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

        self::assertEquals($multimediaId, $event->getAggregateId());
        self::assertEquals($name, $event->getName());
        self::assertEquals($ext, $event->getExtension());
        self::assertEquals($size, $event->getSize());
        self::assertEquals($mime, $event->getMime());
        self::assertEquals($hash, $event->getHash());
    }
}
