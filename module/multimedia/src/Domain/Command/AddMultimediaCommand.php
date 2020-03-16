<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class AddMultimediaCommand implements DomainCommandInterface
{
    /**
     * @var MultimediaId
     */
    private MultimediaId $id;

    /**
     * @var File
     */
    private File $file;

    /**
     * @param MultimediaId $id
     * @param File         $file
     *
     * @throws \Exception
     */
    public function __construct(MultimediaId $id, File $file)
    {
        $this->id = $id;
        $this->file = $file;
    }

    /**
     * @return MultimediaId
     */
    public function getId(): MultimediaId
    {
        return $this->id;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }
}
