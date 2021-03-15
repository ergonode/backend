<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\ChangeUserAvatarCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use League\Flysystem\FilesystemInterface;
use Webmozart\Assert\Assert;

class ChangeUserAvatarCommandHandler
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
    public function __invoke(ChangeUserAvatarCommand $command): void
    {
        $user = $this->repository->load($command->getId());
        Assert::notNull($user);

        $file = $command->getFile();
        $content = file_get_contents($file->getRealPath());
        imagepng(imagecreatefromstring($content), $file->getRealPath());
        imagedestroy(imagecreatefromstring($content));
        $contentPng = file_get_contents($file->getRealPath());

        $filename = sprintf('%s.%s', $user->getId()->getValue(), 'png');

        if ($this->avatarStorage->has($filename)) {
            $this->avatarStorage->update($filename, $contentPng);
        } else {
            $this->avatarStorage->write($filename, $contentPng);
        }

        $user->changeAvatar($filename);
        $this->repository->save($user);
    }
}
