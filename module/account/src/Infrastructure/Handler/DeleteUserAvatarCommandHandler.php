<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\DeleteUserAvatarCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use League\Flysystem\FilesystemInterface;
use Webmozart\Assert\Assert;

class DeleteUserAvatarCommandHandler
{
    private UserRepositoryInterface $repository;

    private FilesystemInterface $avatarStorage;

    public function __construct(
        UserRepositoryInterface $repository,
        FilesystemInterface $avatarStorage
    ) {
        $this->repository = $repository;
        $this->avatarStorage = $avatarStorage;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteUserAvatarCommand $command): void
    {
        $user = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $user,
            User::class,
            sprintf('Can\'t find user with id "%s"', $command->getId())
        );

        $filename = sprintf('%s.%s', $user->getId()->getValue(), 'png');

        $this->avatarStorage->delete($filename);

        $user->removeAvatar();
        $this->repository->save($user);
    }
}
