<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Encoder;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\ValueObject\Password;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as SecurityUserPasswordEncoderInterface;

class DomainUserPasswordEncoder implements UserPasswordEncoderInterface
{
    private SecurityUserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(SecurityUserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * {@inheritDoc}
     */
    public function encode(User $user, Password $password): Password
    {
        $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $password->getValue());

        return new Password($encodedPassword);
    }
}
