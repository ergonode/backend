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
use Ergonode\Importer\Domain\Command\Source\UploadFileCommand;

/**
 */
class UpdateFileCommandHandler
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
     * @param UploadFileCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UploadFileCommand $command)
    {
        $factory = $this->provider->provide($command->getSourceType());

        $source = $factory->create(
            $command->getId(),
            'name',
            $command->getConfiguration(),
        );

        $this->repository->save($source);
    }
}
