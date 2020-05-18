<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Handler;

use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\ExporterFile\Domain\Command\CreateFileExportProfileCommand;

/**
 */
class CreateFileExportProfileCommandHandler
{
    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $repository;

    /**
     * @param ExportProfileRepositoryInterface $repository
     */
    public function __construct(ExportProfileRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateFileExportProfileCommand $command
     */
    public function __invoke(CreateFileExportProfileCommand $command)
    {
        $exportProfile = new FileExportProfile(
            $command->getId(),
            $command->getName()
        );

        $this->repository->save($exportProfile);
    }
}
