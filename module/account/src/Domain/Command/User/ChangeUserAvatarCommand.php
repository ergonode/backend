<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\HttpFoundation\File\File;

class ChangeUserAvatarCommand implements DomainCommandInterface
{
    private UserId $id;

    private File $file;

    public function __construct(UserId $id, File $file)
    {
        $this->id = $id;
        $this->file = $file;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getFile(): File
    {
        return $this->file;
    }
}
