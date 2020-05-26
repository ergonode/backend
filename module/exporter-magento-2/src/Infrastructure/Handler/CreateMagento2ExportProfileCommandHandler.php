<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Infrastructure\Handler;

use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterMagento2\Domain\Command\CreateMagento2ExportProfileCommand;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2ExportCsvProfile;

/**
 */
class CreateMagento2ExportProfileCommandHandler
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
     * @param CreateMagento2ExportProfileCommand $command
     */
    public function __invoke(CreateMagento2ExportProfileCommand $command)
    {
        $exportProfile = new Magento2ExportCsvProfile(
            $command->getId(),
            $command->getName(),
            $command->getFilename(),
            $command->getDefaultLanguage()
        );

        $this->repository->save($exportProfile);
    }
}
