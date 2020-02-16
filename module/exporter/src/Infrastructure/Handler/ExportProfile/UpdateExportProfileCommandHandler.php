<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Handler\ExportProfile;

use Ergonode\Exporter\Domain\Command\ExportProfile\UpdateExportProfileCommand;
use Ergonode\Exporter\Domain\Provider\ExportProfileProvider;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;

/**
 */
class UpdateExportProfileCommandHandler
{
    /**
     * @var ExportProfileProvider
     */
    private ExportProfileProvider $provider;

    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $repository;

    /**
     * @param ExportProfileProvider            $provider
     * @param ExportProfileRepositoryInterface $repository
     */
    public function __construct(ExportProfileProvider $provider, ExportProfileRepositoryInterface $repository)
    {
        $this->provider = $provider;
        $this->repository = $repository;
    }

    /**
     * @param UpdateExportProfileCommand $command
     */
    public function __invoke(UpdateExportProfileCommand $command)
    {
        $factory = $this->provider->provide($command->getType());

        $exportProfile = $factory->create(
            $command->getId(),
            $command->getName(),
            $command->getParameters()
        );

        $this->repository->save($exportProfile);
    }
}
