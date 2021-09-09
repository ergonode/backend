<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\CreateUserCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Infrastructure\Encoder\UserPasswordEncoderInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Account\Domain\ValueObject\Password;

class CreateUserCommandHandler
{
    private UserRepositoryInterface $repository;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    private LanguageQueryInterface $languageQuery;

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
     * @throws \Exception
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $languagePrivilegesCollection = [];
        $activeLanguages = $this->languageQuery->getActive();
        foreach ($activeLanguages as $activeLanguage) {
            $languagePrivilegesCollection[$activeLanguage->getCode()] = new LanguagePrivileges(true, true);
        }

        $plainPasswordUser = $this->createUser($command, $command->getPassword(), $languagePrivilegesCollection);
        $encodedPassword = $this->userPasswordEncoder->encode($plainPasswordUser, $command->getPassword());
        $user = $this->createUser($command, $encodedPassword, $languagePrivilegesCollection);

        $this->repository->save($user);
    }

    private function createUser(CreateUserCommand $command, Password $password, array $languagePrivilege): User
    {
        return new User(
            $command->getId(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail(),
            $command->getLanguage(),
            $password,
            $command->getRoleId(),
            $languagePrivilege,
            $command->isActive()
        );
    }
}
