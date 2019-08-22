<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Handler;

use Ergonode\Core\Domain\Command\CreateLanguageCommand;
use Ergonode\Core\Domain\Factory\LanguageFactory;
use Ergonode\Core\Domain\Repository\LanguageRepositoryInterface;

/**
 */
class CreateLanguageCommandHandler
{
    /**
     * @var LanguageFactory
     */
    private $factory;

    /**
     * @var CategoryRepositoryInterface
     */
    private $repository;

    /**
     * @param LanguageFactory             $factory
     * @param LanguageRepositoryInterface $repository
     */
    public function __construct(LanguageFactory $factory, LanguageRepositoryInterface $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @param CreateLanguageCommand $command
     */
    public function __invoke(CreateLanguageCommand $command)
    {
        $category = $this->factory->create(
            $command->getId(),
            $command->getCode(),
            $command->getName(),
            $command->getActive()
        );

        $this->repository->save($category);
    }
}
