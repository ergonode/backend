<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\StartImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StartImportCommandHandler
{
    /**
     * @var ImportRepositoryInterface
     */
    private ImportRepositoryInterface $repository;

    /**
     * @param ImportRepositoryInterface $repository
     */
    public function __construct(ImportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param StartImportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(StartImportCommand $command)
    {
        $import = $this->repository->load($command->getId());

        Assert::notNull($import);
        Assert::isInstanceOf($import, Import::class);

        $import->start();

        $this->repository->save($import);
    }
}
