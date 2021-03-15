<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Handler;

use Ergonode\Core\Domain\Command\CreateUnitCommand;
use Ergonode\Core\Domain\Factory\UnitFactory;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;

class CreateUnitCommandHandler
{
    private UnitRepositoryInterface $repository;

    private UnitFactory $factory;

    public function __construct(UnitRepositoryInterface $repository, UnitFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateUnitCommand $command): void
    {
        $unit = $this->factory->create(
            $command->getId(),
            $command->getName(),
            $command->getSymbol()
        );
        $this->repository->save($unit);
    }
}
