<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Handler;

use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterFile\Domain\Command\UpdateFileExportProfileCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;

/**
 */
class UpdateFileExportProfileCommandHandler
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
     * @param UpdateFileExportProfileCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(UpdateFileExportProfileCommand $command)
    {
        /** @var FileExportProfile $exportProfile */
        $exportProfile = $this->repository->load($command->getId());
        $exportProfile->setName($command->getName());
        $exportProfile->setFormat($command->getFormat());

        $this->repository->save($exportProfile);
    }
}
