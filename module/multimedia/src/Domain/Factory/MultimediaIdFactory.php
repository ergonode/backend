<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Factory;

use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Ramsey\Uuid\Uuid;

/**
 */
class MultimediaIdFactory
{
    /**
     * @param \SplFileInfo $file
     *
     * @return MultimediaId
     */
    public static function createFromFile(\SplFileInfo $file): MultimediaId
    {
        return new MultimediaId(Uuid::uuid5(MultimediaId::NAMESPACE, sha1_file($file->getRealPath()))->toString());
    }
}
