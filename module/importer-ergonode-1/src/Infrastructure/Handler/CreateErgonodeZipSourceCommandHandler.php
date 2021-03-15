<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Handler;

use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterErgonode1\Domain\Command\CreateErgonodeZipSourceCommand;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;

class CreateErgonodeZipSourceCommandHandler
{
    private SourceRepositoryInterface $repository;

    public function __construct(SourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateErgonodeZipSourceCommand $command): void
    {
        $source = new ErgonodeZipSource(
            $command->getId(),
            $command->getName(),
            $command->getImport()
        );

        $this->repository->save($source);
    }
}
