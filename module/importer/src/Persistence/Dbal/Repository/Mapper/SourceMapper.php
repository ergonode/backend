<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Mapper;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;

/**
 */
class SourceMapper
{
    /**
     * @param AbstractSource $source
     *
     * @return array
     */
    public function map(AbstractSource $source): array
    {
        return [
            'id' => $source->getId()->getValue(),
            'configuration' => \json_encode($source->getConfiguration(), JSON_THROW_ON_ERROR, 512),
            'type' => \get_class($source),
        ];
    }
}
