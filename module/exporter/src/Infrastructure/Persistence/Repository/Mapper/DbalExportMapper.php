<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Exporter\Domain\Entity\Export;

/**
 */
class DbalExportMapper
{
    /**
     * @param Export $export
     *
     * @return array
     */
    public function map(Export $export): array
    {
        return [
            'id' => $export->getId(),
            'status' => $export->getStatus(),
            'channel_id' => $export->getChannelId()->getValue(),
            'items' => $export->getItems(),
            'started_at' => $export->getStartedAt(),
            'ended_at' => $export->getEndedAt(),
        ];
    }
}
