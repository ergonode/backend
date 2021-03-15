<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\DeleteImportCommand;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\Importer\Infrastructure\Service\ImporterFileRemover;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;

class DeleteImportCommandHandler
{
    private ImporterFileRemover $importerFileRemover;


    private ImportQueryInterface $importQuery;

    public function __construct(
        ImporterFileRemover $importerFileRemover,
        ImportQueryInterface $importQuery
    ) {
        $this->importerFileRemover = $importerFileRemover;
        $this->importQuery = $importQuery;
    }

    public function __invoke(DeleteImportCommand $command): void
    {
        $sourceType = $this->importQuery->getSourceTypeByImportId($command->getId());
        if (Magento1CsvSource::TYPE  === $sourceType) {
            $fileName = $this->importQuery->getFileNameByImportId($command->getId());
            $this->importerFileRemover->remove($fileName);
        }
    }
}
