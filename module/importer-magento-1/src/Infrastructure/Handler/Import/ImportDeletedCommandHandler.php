<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\ImportDeletedCommand;
use Ergonode\Importer\Infrastructure\Service\ImporterFileRemover;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;

class ImportDeletedCommandHandler
{
    private ImporterFileRemover $importerFileRemover;

    public function __construct(ImporterFileRemover $importerFileRemover)
    {
        $this->importerFileRemover = $importerFileRemover;
    }

    public function __invoke(ImportDeletedCommand $command): void
    {
        if (Magento1CsvSource::TYPE === $command->getSourceType()) {
            $this->importerFileRemover->remove($command->getFileName());
        }
    }
}
