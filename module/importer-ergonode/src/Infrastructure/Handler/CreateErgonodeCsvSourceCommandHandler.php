<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Infrastructure\Handler;

use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterErgonode\Domain\Command\CreateErgonodeCsvSourceCommand;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeCsvSource;

/**
 */
final class CreateErgonodeCsvSourceCommandHandler
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
     * @param CreateErgonodeCsvSourceCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateErgonodeCsvSourceCommand $command)
    {
        $source = new ErgonodeCsvSource(
            $command->getId(),
            $command->getName(),
            $command->getDefaultLanguage(),
            $command->getLanguages(),
            $command->getAttributes(),
            $command->getImport(),
            $command->getHost()
        );

        $this->repository->save($source);
    }
}
