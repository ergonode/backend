<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Channel\Infrastructure\Service\ZipFileRemover;
use Ergonode\Channel\Domain\Command\Export\DeleteExportCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class DeleteExportCommandHandler
{
    private ZipFileRemover $zipFileRemover;


    private ExportQueryInterface $exportQuery;

    public function __construct(
        ZipFileRemover $zipFileRemover,
        ExportQueryInterface $exportQuery
    ) {
        $this->zipFileRemover = $zipFileRemover;
        $this->exportQuery = $exportQuery;
    }

    public function __invoke(DeleteExportCommand $command): void
    {
        $channelType = $this->exportQuery->getChannelTypeByExportId($command->getExportId());
        if (FileExportChannel::getType() === $channelType) {
            $this->zipFileRemover->remove($command->getExportId());
        }
    }
}
