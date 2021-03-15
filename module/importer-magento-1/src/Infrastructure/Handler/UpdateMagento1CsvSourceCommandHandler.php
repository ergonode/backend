<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Handler;

use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento1\Domain\Command\UpdateMagento1CsvSourceCommand;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;

class UpdateMagento1CsvSourceCommandHandler
{
    private SourceRepositoryInterface $repository;

    public function __construct(SourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateMagento1CsvSourceCommand $command): void
    {
        /** @var Magento1CsvSource $source */
        $source = $this->repository->load($command->getId());
        $source->setName($command->getName());
        $source->setAttributes($command->getAttributes());
        $source->setHost($command->getHost());
        $source->setDefaultLanguage($command->getDefaultLanguage());
        $source->setImport($command->getImport());
        $source->setLanguages($command->getLanguages());

        $this->repository->save($source);
    }
}
