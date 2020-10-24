<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Handler;

use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterErgonode\Domain\Command\UpdateErgonodeZipSourceCommand;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeZipSource;

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
    public function __invoke(UpdateErgonodeZipSourceCommand $command)
    {
        /** @var ErgonodeZipSource $source */
        $source = $this->repository->load($command->getId());
        $source->setName($command->getName());
        $source->setImport($command->getImport());

        $this->repository->save($source);
    }
}
