<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler\User;

use Ergonode\Account\Domain\Command\User\ApplyUserResetTokenCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Domain\Repository\UserResetPasswordTokenRepositoryInterface;
use Ergonode\Account\Infrastructure\Encoder\UserPasswordEncoderInterface;
use Webmozart\Assert\Assert;

class ApplyUserResetTokenCommandHandler
{
    private UserResetPasswordTokenRepositoryInterface $tokenRepository;

    private UserRepositoryInterface $userRepository;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(
        UserResetPasswordTokenRepositoryInterface $tokenRepository,
        UserRepositoryInterface $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function __invoke(ApplyUserResetTokenCommand $command): void
    {
        $token = $this->tokenRepository->load($command->getToken());
        Assert::notNull($token);

        $user = $this->userRepository->load($token->getUserId());
        Assert::notNull($user);

        $encodedPassword = $this->userPasswordEncoder->encode($user, $command->getPassword());
        if ($user->getPassword() !== $encodedPassword->getValue()) {
            $user->changePassword($encodedPassword);
        }
        $token->setConsumed(new \DateTime());

        $this->userRepository->save($user);
        $this->tokenRepository->save($token);
    }
}
