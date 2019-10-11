<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler\Role;

use Ergonode\Account\Domain\Command\Role\CreateRoleCommand;
use Ergonode\Account\Domain\Factory\RoleFactory;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;

/**
 */
class CreateRoleCommandHandler
{
    /**
     * @var RoleRepositoryInterface
     */
    private $repository;

    /**
     * @var RoleFactory
     */
    private $factory;

    /**
     * @param RoleRepositoryInterface $repository
     * @param RoleFactory             $factory
     */
    public function __construct(RoleRepositoryInterface $repository, RoleFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param CreateRoleCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateRoleCommand $command)
    {
        $role = $this->factory->create(
            $command->getId(),
            $command->getName(),
            $command->getDescription(),
            $command->getPrivileges()
        );

        $this->repository->save($role);
    }
}
