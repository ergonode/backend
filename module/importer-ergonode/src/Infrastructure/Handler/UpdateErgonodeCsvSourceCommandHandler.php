<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Infrastructure\Handler;

use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterErgonode\Domain\Command\UpdateErgonodeCsvSourceCommand;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeCsvSource;

/**
 */
final class UpdateErgonodeCsvSourceCommandHandler
{
    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $repository;

    /**
     * @param SourceRepositoryInterface $repository
     */
    public function __construct(SourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateErgonodeCsvSourceCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateErgonodeCsvSourceCommand $command)
    {
        /** @var ErgonodeCsvSource $source */
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
