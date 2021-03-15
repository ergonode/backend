<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Handler\Export;

use Ergonode\Channel\Domain\Command\Export\DeleteExportCommand;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
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
