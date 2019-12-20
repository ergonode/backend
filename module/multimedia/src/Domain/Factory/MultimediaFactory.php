<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Factory;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class MultimediaFactory
{
    /**
     * @param MultimediaId $id
     * @param string       $name
     * @param File         $file
     * @param string       $crc
     *
     * @return Multimedia
     *
     * @throws \Exception
     */
    public static function createFromFile(MultimediaId $id, string $name, File $file, string $crc): Multimedia
    {
        return new Multimedia($id, $name, $file->getExtension(), $file->getSize(), $crc, $file->getMimeType());
    }
}
