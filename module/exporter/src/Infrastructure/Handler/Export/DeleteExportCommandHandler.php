<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Exporter\Infrastructure\Handler\Export;

use Ergonode\Exporter\Domain\Command\Export\DeleteExportCommand;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteExportCommandHandler
{
    private ExportRepositoryInterface $exportRepository;

    public function __construct(ExportRepositoryInterface $exportRepository)
    {
        $this->exportRepository = $exportRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteExportCommand $command): void
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class, sprintf('Can\'t find export with id %s', $command->getExportId()));
        $this->exportRepository->delete($export);
    }
}
