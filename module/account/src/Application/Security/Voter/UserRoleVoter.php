<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Security\Voter;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\PrivilegeEndPoint;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;

class UserRoleVoter extends Voter
{
    private RoleRepositoryInterface $repository;

    private PrivilegeQueryInterface $query;

    public function __construct(RoleRepositoryInterface $repository, PrivilegeQueryInterface $query)
    {
        $this->repository = $repository;
        $this->query = $query;
    }


    /**
     * {@inheritDoc}
     */
    public function supports($attribute, $subject): bool
    {
        $privileges = $this->query->getPrivilegesEndPoint();

        return in_array($attribute, array_column($privileges, 'name'), true);
    }

    /**
     * {@inheritDoc}
     */
    public function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof User) {
            return true;
        }

        $role = $this->repository->load($user->getRoleId());
        if (!$role instanceof Role) {
            throw new \RuntimeException(sprintf('Role by id "%s" not found', $user->getRoleId()->getValue()));
        }

        $privileges = $this->query->getEndpointPrivilegesByPrivileges($role->getPrivileges());


        $attributePrivilege = new PrivilegeEndPoint($attribute);

        foreach ($privileges as $privilege) {
            if ($privilege->isEqual($attributePrivilege)) {
                return true;
            }
        }

        return false;
    }
}
