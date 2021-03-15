<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Handler;

use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterErgonode1\Domain\Command\UpdateErgonodeZipSourceCommand;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;

class UpdateErgonodeZipSourceCommandHandler
{
    private SourceRepositoryInterface $repository;

    public function __construct(SourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateErgonodeZipSourceCommand $command): void
    {
        /** @var ErgonodeZipSource $source */
        $source = $this->repository->load($command->getId());
        $source->setName($command->getName());
        $source->setImport($command->getImport());

        $this->repository->save($source);
    }
}
