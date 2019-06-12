<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Repository\Mapper;

use Ergonode\Multimedia\Domain\Entity\Multimedia;

/**
 */
class MultimediaMapper
{
    /**
     * @param Multimedia $multimedia
     *
     * @return array
     */
    public function map(Multimedia $multimedia): array
    {
        return [
            'id' => $multimedia->getId(),
            'name' => $multimedia->getName(),
            'extension' => $multimedia->getExtension(),
            'size' => $multimedia->getSize(),
            'mime' => $multimedia->getMime(),
            'crc' => $multimedia->getCrc(),
        ];
    }
}
