<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\AddAvatarCommand;
use Ergonode\Multimedia\Domain\Entity\Avatar;
use Ergonode\Multimedia\Domain\Repository\AvatarRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\Multimedia\Infrastructure\Storage\ResourceStorageInterface;

/**
 */
class AddAvatarCommandHandler
{
    /**
     * @var HashCalculationServiceInterface
     */
    private HashCalculationServiceInterface $hashService;

    /**
     * @var AvatarRepositoryInterface
     */
    private AvatarRepositoryInterface $repository;

    /**
     * @var ResourceStorageInterface
     */
    private ResourceStorageInterface $avatarStorage;

    /**
     * @param HashCalculationServiceInterface $hashService
     * @param AvatarRepositoryInterface       $repository
     * @param ResourceStorageInterface        $avatarStorage
     */
    public function __construct(
        HashCalculationServiceInterface $hashService,
        AvatarRepositoryInterface $repository,
        ResourceStorageInterface $avatarStorage
    ) {
        $this->hashService = $hashService;
        $this->repository = $repository;
        $this->avatarStorage = $avatarStorage;
    }

    /**
     * @param AddAvatarCommand $command
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __invoke(AddAvatarCommand $command): void
    {
        $id = $command->getId();
        $file = $command->getFile();
        $hash = $this->hashService->calculateHash($file);

        $extension = $file->getExtension();
        if (empty($extension) || '.' === $extension) {
            $extension = $file->guessExtension();
        }

        $filename = sprintf('%s.%s', $hash->getValue(), $extension);

        if (!$this->avatarStorage->has($filename)) {
            $content = file_get_contents($file->getRealPath());
            $this->avatarStorage->write($filename, $content);
        }

        $info = $this->avatarStorage->info($filename);

        $avatar = new Avatar(
            $id,
            $extension,
            $info['size'],
            $hash,
            $info['mime']
        );

        $this->repository->save($avatar);
    }
}
