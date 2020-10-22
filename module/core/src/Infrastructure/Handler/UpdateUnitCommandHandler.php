<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Handler;

use Ergonode\Core\Domain\Command\UpdateUnitCommand;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateUnitCommandHandler
{
    /**
     * @var UnitRepositoryInterface
     */
    private UnitRepositoryInterface $repository;

    /**
     * @param UnitRepositoryInterface $repository
     */
    public function __construct(UnitRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateUnitCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateUnitCommand $command)
    {
        /** @var Unit $unit */
        $unit = $this->repository->load($command->getId());
        Assert::notNull($unit);
        $unit->changeName($command->getName());
        $unit->changeSymbol($command->getSymbol());
        $this->repository->save($unit);
    }
}
