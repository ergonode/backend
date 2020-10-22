<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Exporter\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\Exporter\Domain\Entity\ExportLine;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\AggregateId;

interface ExportLineRepositoryInterface
{
    /**
     * @param ExportLine $line
     *
     * @throws DBALException
     */
    public function save(ExportLine $line): void;

    /**
     * @param ExportId    $exportId
     * @param AggregateId $objectId
     *
     * @return ExportLine|null
     */
    public function load(ExportId $exportId, AggregateId $objectId): ?ExportLine;
}
