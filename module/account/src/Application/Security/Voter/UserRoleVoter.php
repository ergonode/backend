<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Security\Voter;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;

class UserRoleVoter extends Voter implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var RoleRepositoryInterface
     */
    private RoleRepositoryInterface $repository;

    /**
     * @var PrivilegeQueryInterface
     */
    private PrivilegeQueryInterface $query;

    /**
     * @param RoleRepositoryInterface $repository
     * @param PrivilegeQueryInterface $query
     */
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
        $privileges = $this->query->getPrivileges();

        return in_array($attribute, array_column($privileges, 'code'), true);
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

        $result = false;
        $attributePrivilege = new Privilege($attribute);
        /** @var Privilege $privilege */
        foreach ($role->getPrivileges() as $privilege) {
            if ($privilege->isEqual($attributePrivilege)) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}
