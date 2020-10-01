<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\CreateUserCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Account\Infrastructure\Encoder\UserPasswordEncoderInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;

/**
 */
class CreateUserCommandHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $languageQuery;

    /**
     * @param UserRepositoryInterface      $repository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param LanguageQueryInterface       $languageQuery
     */
    public function __construct(
        UserRepositoryInterface $repository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        LanguageQueryInterface $languageQuery
    ) {
        $this->repository = $repository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->languageQuery = $languageQuery;
    }

    /**
     * @param CreateUserCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateUserCommand $command)
    {
        $languagePrivilegesCollection = [];
        $activeLanguages = $this->languageQuery->getActive();
        foreach ($activeLanguages as $activeLanguage) {
            $languagePrivilegesCollection[$activeLanguage->getCode()] = new LanguagePrivileges(true, true);
        }
        $user = new User(
            $command->getId(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail(),
            $command->getLanguage(),
            $command->getPassword(),
            $command->getRoleId(),
            $languagePrivilegesCollection,
            $command->isActive()
        );

        $encodedPassword = $this->userPasswordEncoder->encode($user, $command->getPassword());
        $user->changePassword($encodedPassword);

        $this->repository->save($user);
    }
}
