<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\Channel\Domain\Entity\ExportLine;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\AggregateId;

interface ExportLineRepositoryInterface
{
    /**
     * @throws DBALException
     */
    public function save(ExportLine $line): void;

    public function load(ExportId $exportId, AggregateId $objectId): ?ExportLine;
}
