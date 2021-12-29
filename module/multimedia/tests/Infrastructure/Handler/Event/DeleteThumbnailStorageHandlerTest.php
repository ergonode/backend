<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Handler\Event;

use Ergonode\Multimedia\Domain\Event\MultimediaDeletedEvent;
use Ergonode\Multimedia\Infrastructure\Handler\Event\DeleteThumbnailStorageHandler;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use League\Flysystem\FilesystemInterface;
use PHPUnit\Framework\TestCase;

class DeleteThumbnailStorageHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $event = $this->createMock(MultimediaDeletedEvent::class);
        $event->method('getAggregateId')->willReturn(MultimediaId::generate());

        $thumbnailStorage = $this->createMock(FilesystemInterface::class);
        $thumbnailStorage->expects(self::once())->method('has')->willReturn(true);
        $thumbnailStorage->expects(self::once())->method('delete')->willReturn(true);

        $handler = new DeleteThumbnailStorageHandler($thumbnailStorage);
        $handler->__invoke($event);
    }
}
