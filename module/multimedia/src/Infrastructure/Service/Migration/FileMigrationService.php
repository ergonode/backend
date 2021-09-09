<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Migration;

use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use League\Flysystem\FilesystemInterface;

class FileMigrationService
{
    private FilesystemInterface $multimediaStorage;

    public function __construct(FilesystemInterface $multimediaStorage)
    {
        $this->multimediaStorage = $multimediaStorage;
    }

    public function migrateFile(MultimediaId $multimediaId, Hash $hash, string $extension): void
    {
        $oldFilename = sprintf('%s.%s', $hash->getValue(), $extension);
        $newFilename = sprintf('%s.%s', $multimediaId->getValue(), $extension);

        if (!$this->multimediaStorage->has($oldFilename)) {
            return;
        }

        $this->multimediaStorage->rename($oldFilename, $newFilename);
    }
}
