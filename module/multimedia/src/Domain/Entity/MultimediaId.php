<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class MultimediaId extends AbstractId
{
    public const NAMESPACE = '690c9b97-57bc-4c71-9b62-37093c578836';

    /**
     * @param \SplFileInfo $file
     *
     * @return MultimediaId
     */
    public static function createFromFile(\SplFileInfo $file): self
    {
        return new self(Uuid::uuid5(self::NAMESPACE, file_get_contents($file->getRealPath()))->toString());
    }
}
