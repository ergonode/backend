<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler\Role;

use Ergonode\Account\Domain\Command\Role\UpdateRoleCommand;
use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateRoleCommandHandler
{
    private RoleRepositoryInterface $repository;

    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateRoleCommand $command): void
    {
        $role = $this->repository->load($command->getId());
        Assert::isInstanceOf($role, Role::class, sprintf('Can\'t find Role with id %s', $command->getId()));
        $role->changeName($command->getName());
        $role->changeDescription($command->getDescription());
        $role->changesPrivileges($command->getPrivileges());

        $this->repository->save($role);
    }
}
