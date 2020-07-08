<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\ChangeUserAvatarCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Infrastructure\Storage\FilesystemAvatarStorage;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

/**
 */
class ChangeUserAvatarCommandHandler
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
     * @param ChangeUserAvatarCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ChangeUserAvatarCommand $command)
    {
        $user = $this->repository->load($command->getId());
        Assert::notNull($user);

        $file = $command->getFile();
        $content = file_get_contents($file->getRealPath());
        imagepng(imagecreatefromstring($content), $file->getRealPath());
        imagedestroy(imagecreatefromstring($content));
        $contentPng = file_get_contents($file->getRealPath());

        $filename = sprintf('%s.%s', $user->getId()->getValue(), 'png');

        if ($this->storage->has($filename)) {
            $this->storage->update($filename, $contentPng);
        } else {
            $this->storage->write($filename, $contentPng);
        }

        $user->changeAvatar($filename);
        $this->repository->save($user);
    }
}
