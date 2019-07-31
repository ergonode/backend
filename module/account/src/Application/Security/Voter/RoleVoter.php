<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
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
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 */
class RoleVoter extends Voter implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($attribute, $subject): bool
    {
        return true;
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

        $role = $this->roleRepository->load($user->getRoleId());
        if (!$role instanceof Role) {
            throw new \RuntimeException(sprintf('Role by id "%s" not found', $user->getRoleId()->getValue()));
        }

        $result = VoterInterface::ACCESS_DENIED;
        /** @var Privilege $privilege */
        foreach ($role->getPrivileges() as $privilege) {
            if ($privilege->isEqual(new Privilege($attribute))) {
                $result = VoterInterface::ACCESS_GRANTED;
                break;
            }
        }

        return (bool) $result;
    }
}
