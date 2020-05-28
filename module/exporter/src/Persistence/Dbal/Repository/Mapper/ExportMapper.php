<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository\Mapper;

use Ergonode\Exporter\Domain\Entity\Export;

/**
 */
class ExportMapper
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
            'export_profile_id' => $export->getExportProfileId()->getValue(),
            'started_at' => $export->getStartedAt() ? $export->getStartedAt()->format('Y-m-d H:i:s') : null,
            'ended_at' => $export->getEndedAt() ? $export->getEndedAt()->format('Y-m-d H:i:s') : null,
        ];
    }
}
