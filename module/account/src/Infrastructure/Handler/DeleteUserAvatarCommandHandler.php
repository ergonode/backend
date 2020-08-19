<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\DeleteUserAvatarCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Infrastructure\Storage\FilesystemAvatarStorage;
use Webmozart\Assert\Assert;

/**
 */
class DeleteUserAvatarCommandHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    /**
     * @var FilesystemAvatarStorage
     */
    private FilesystemAvatarStorage $storage;

    /**
     * @param UserRepositoryInterface $repository
     * @param FilesystemAvatarStorage $storage
     */
    public function __construct(
        UserRepositoryInterface $repository,
        FilesystemAvatarStorage $storage
    ) {
        $this->repository = $repository;
        $this->storage = $storage;
    }

    /**
     * @param DeleteUserAvatarCommand $command
     *
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

        $this->storage->delete($filename);

        $user->removeAvatar();
        $this->repository->save($user);
    }
}
