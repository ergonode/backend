<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Infrastructure\Handler;

use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterMagento2\Domain\Command\UpdateMagento2ExportProfileCommand;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2ExportCsvProfile;

/**
 */
class UpdateMagento2ExportProfileCommandHandler
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
     * @param UpdateMagento2ExportProfileCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(UpdateMagento2ExportProfileCommand $command)
    {
        /** @var Magento2ExportCsvProfile $exportProfile */
        $exportProfile = $this->repository->load($command->getId());
        $exportProfile->setName($command->getName());
        $exportProfile->setFilename($command->getFilename());
        $exportProfile->setDefaultLanguage($command->getDefaultLanguage());

        $this->repository->save($exportProfile);
    }
}
