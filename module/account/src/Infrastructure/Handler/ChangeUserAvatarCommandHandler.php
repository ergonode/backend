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
     * @var HashCalculationServiceInterface
     */
    private HashCalculationServiceInterface $hashService;

    /**
     * @var FilesystemAvatarStorage
     */
    private FilesystemAvatarStorage $storage;

    /**
     * @param UserRepositoryInterface         $repository
     * @param HashCalculationServiceInterface $hashService
     * @param FilesystemAvatarStorage         $storage
     */
    public function __construct(
        UserRepositoryInterface $repository,
        HashCalculationServiceInterface $hashService,
        FilesystemAvatarStorage $storage
    ) {
        $this->repository = $repository;
        $this->hashService = $hashService;
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
        $hash = $this->hashService->calculateHash($file);

        $extension = $file->getExtension();
        if (empty($extension) || '.' === $extension) {
            $extension = $file->guessExtension();
        }

        $filename = sprintf('%s.%s', $hash->getValue(), $extension);

        if (!$this->storage->has($filename)) {
            $content = file_get_contents($file->getRealPath());
            $this->storage->write($filename, $content);
        }

        $user->changeAvatar($filename);
        $this->repository->save($user);
    }
}
