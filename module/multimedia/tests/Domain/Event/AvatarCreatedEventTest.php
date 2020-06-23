<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Event;

use Ergonode\Multimedia\Domain\Event\AvatarCreatedEvent;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AvatarCreatedEventTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testEventCreate(): void
    {
        /** @var AvatarId | MockObject $multimediaId */
        $multimediaId = $this->createMock(AvatarId::class);
        $ext = 'extension';
        $size = 123;
        $hash = $this->createMock(Hash::class);
        $mime = 'text/json';
        $event = new AvatarCreatedEvent(
            $multimediaId,
            $ext,
            $size,
            $hash,
            $mime
        );

        $this->assertEquals($multimediaId, $event->getAggregateId());
        $this->assertEquals($ext, $event->getExtension());
        $this->assertEquals($size, $event->getSize());
        $this->assertEquals($mime, $event->getMime());
        $this->assertEquals($hash, $event->getHash());
    }
}
