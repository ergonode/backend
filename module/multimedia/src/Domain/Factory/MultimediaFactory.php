<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Factory;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class MultimediaFactory
{
    /**
     * @param MultimediaId $id
     * @param string       $name
     * @param File         $file
     * @param Hash         $hash
     *
     * @return Multimedia
     *
     * @throws \Exception
     */
    public function create(MultimediaId $id, string $name, File $file, Hash $hash): Multimedia
    {
        return new Multimedia($id, $name, $file->getExtension(), $file->getSize(), $hash, $file->getMimeType());
    }
}
