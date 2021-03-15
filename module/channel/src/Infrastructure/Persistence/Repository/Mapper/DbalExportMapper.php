<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Channel\Domain\Entity\Export;

class DbalExportMapper
{
    /**
     * @return array
     */
    public function map(Export $export): array
    {
        return [
            'id' => $export->getId(),
            'status' => $export->getStatus(),
            'channel_id' => $export->getChannelId()->getValue(),
            'started_at' => $export->getStartedAt(),
            'ended_at' => $export->getEndedAt(),
        ];
    }
}
