<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Source;

use Ergonode\Importer\Domain\Command\Source\CreateSourceCommand;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;

/**
 */
class CreateSourceCommandHandler
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
     * @param CreateSourceCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateSourceCommand $command)
    {
        $source = new Magento2CsvSource(
            $command->getId(),
            $command->getFilename(),
        );

        $this->repository->save($source);
    }
}
