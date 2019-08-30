<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\Handler;

use Ergonode\Reader\Domain\Command\CreateReaderCommand;
use Ergonode\Reader\Domain\Factory\ReaderFactory;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;

/**
 */
class CreateReaderCommandHandler
{
    /**
     * @var ReaderFactory
     */
    private $factory;

    /**
     * @var ReaderRepositoryInterface
     */
    private $repository;

    /**
     * @param ReaderFactory             $factory
     * @param ReaderRepositoryInterface $repository
     */
    public function __construct(ReaderFactory $factory, ReaderRepositoryInterface $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @param CreateReaderCommand $command
     */
    public function __invoke(CreateReaderCommand $command)
    {
        $reader = $this->factory->create(
            $command->getId(),
            $command->getName(),
            $command->getType(),
            $command->getConfiguration(),
            $command->getFormatters()
        );

        $this->repository->save($reader);
    }
}
