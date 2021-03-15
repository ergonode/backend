<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler\Role;

use Ergonode\Account\Domain\Command\Role\CreateRoleCommand;
use Ergonode\Account\Domain\Factory\RoleFactory;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;

class CreateRoleCommandHandler
{
    private RoleRepositoryInterface $repository;

    private RoleFactory $factory;

    public function __construct(RoleRepositoryInterface $repository, RoleFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateRoleCommand $command): void
    {
        $role = $this->factory->create(
            $command->getId(),
            $command->getName(),
            $command->getDescription(),
            $command->getPrivileges(),
            $command->isHidden()
        );

        $this->repository->save($role);
    }
}
