<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler\Role;

use Ergonode\Account\Domain\Command\Role\DeleteRoleCommand;
use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteRoleCommandHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @var RelationshipsResolverInterface
     */
    private $relationshipsResolver;

    /**
     * @param UserRepositoryInterface        $repository
     * @param RelationshipsResolverInterface $relationshipsResolver
     */
    public function __construct(
        UserRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @param DeleteRoleCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteRoleCommand $command)
    {
        $role = $this->repository->load($command->getId());
        Assert::isInstanceOf($role, Role::class, sprintf('Can\'t find role with ID "%s"', $command->getId()));

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (!$relationships->isEmpty()) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($role);
    }
}
