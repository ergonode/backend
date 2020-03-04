<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Source;

use Ergonode\Importer\Domain\Command\Source\CreateSourceCommand;
use Ergonode\Importer\Domain\Provider\SourceFactoryProvider;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;

/**
 */
class CreateSourceCommandHandler
{
    /**
     * @var SourceFactoryProvider
     */
    private SourceFactoryProvider $provider;

    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $repository;

    /**
     * @param SourceFactoryProvider     $provider
     * @param SourceRepositoryInterface $repository
     */
    public function __construct(SourceFactoryProvider $provider, SourceRepositoryInterface $repository)
    {
        $this->provider = $provider;
        $this->repository = $repository;
    }

    /**
     * @param CreateSourceCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateSourceCommand $command)
    {
        $factory = $this->provider->provide($command->getSourceType());

        $source = $factory->create(
            $command->getId(),
            $command->getName(),
            $command->getConfiguration(),
        );

        $this->repository->save($source);
    }
}
