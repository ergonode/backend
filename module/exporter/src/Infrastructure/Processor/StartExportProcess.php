<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Processor;

use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;

/**
 */
class StartExportProcess
{
    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $repository;

    /**
     * @param ChannelId $id
     *
     * @throws \Exception
     */
    public function process(ChannelId $id): void
    {
        $export = new Export(ExportId::generate(), $id);

        $this->repository->save($export);
    }
}