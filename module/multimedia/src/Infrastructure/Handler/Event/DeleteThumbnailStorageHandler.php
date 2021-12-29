<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Handler\Event;

use Ergonode\Multimedia\Domain\Event\MultimediaDeletedEvent;
use League\Flysystem\FilesystemInterface;

class DeleteThumbnailStorageHandler
{
    private FilesystemInterface $thumbnailStorage;

    public function __construct(FilesystemInterface $thumbnailStorage)
    {
        $this->thumbnailStorage = $thumbnailStorage;
    }

    public function __invoke(MultimediaDeletedEvent $event): void
    {
        $filename = sprintf('%s/%s.png', 'default', $event->getAggregateId()->getValue());

        if ($this->thumbnailStorage->has($filename)) {
            $this->thumbnailStorage->delete($filename);
        }
    }
}
