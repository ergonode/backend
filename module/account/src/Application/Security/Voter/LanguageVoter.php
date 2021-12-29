<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Core\Domain\ValueObject\Language;

class LanguageVoter extends Voter
{
    public const EDIT = 'edit';
    public const READ = 'read';

    /**
     * {@inheritDoc}
     */
    public function supports($attribute, $subject): bool
    {

        if (!in_array($attribute, [self::READ, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Language) {
            return false;
        }

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
            return false;
        }

        /** @var Language $language */
        $language = $subject;

        if (self::EDIT === $attribute) {
            return $user->hasEditLanguagePrivilege($language);
        }

        if (self::READ === $attribute) {
            return $user->hasReadLanguagePrivilege($language);
        }

        throw new \LogicException('This code should not be reached!');
    }
}
