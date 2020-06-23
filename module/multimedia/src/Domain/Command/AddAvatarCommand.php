<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class AddAvatarCommand implements DomainCommandInterface
{
    /**
     * @var AvatarId
     */
    private AvatarId $id;

    /**
     * @var File
     */
    private File $file;

    /**
     * @param AvatarId $id
     * @param File     $file
     *
     * @throws \Exception
     */
    public function __construct(AvatarId $id, File $file)
    {
        $this->id = $id;
        $this->file = $file;
    }

    /**
     * @return AvatarId
     */
    public function getId(): AvatarId
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
