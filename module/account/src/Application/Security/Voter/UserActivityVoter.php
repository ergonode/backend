<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Security\Voter;

use Ergonode\Account\Domain\Entity\User;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 */
class UserActivityVoter extends Voter implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function supports($attribute, $subject): bool
    {
        return 'IS_AUTHENTICATED_ANONYMOUSLY' !== $attribute;
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

        return $user->isActive();
    }
}
