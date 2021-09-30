<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;

class AddMultimediaCommandHandler
{
    private HashCalculationServiceInterface $hashService;

    private MultimediaRepositoryInterface $repository;

    private FilesystemInterface $multimediaStorage;

    private MultimediaExtensionProvider $provider;

    public function __construct(
        HashCalculationServiceInterface $hashService,
        MultimediaRepositoryInterface $repository,
        FilesystemInterface $multimediaStorage,
        MultimediaExtensionProvider $provider
    ) {
        $this->hashService = $hashService;
        $this->repository = $repository;
        $this->multimediaStorage = $multimediaStorage;
        $this->provider = $provider;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(AddMultimediaCommand $command): void
    {
        $id = $command->getId();
        /** @var UploadedFile $file */
        $file = $command->getFile();
        $hash = $this->hashService->calculateHash($file);
        $originalName = $command->getName() ?? $file->getClientOriginalName();

        $extension = $file->getClientOriginalExtension();
        if (empty($extension) || '.' === $extension) {
            $extension = $file->guessExtension();
        }

        if (mb_strpos($originalName, '/')) {
            throw new \LogicException('Multimedia the name can\'t contains "/" character.');
        }

        if (!in_array($extension, $this->provider->dictionary(), true)) {
            throw new \LogicException('Multimedia type {type} is not allowed ', ['{type}' => $extension]);
        }

        $filename = sprintf('%s.%s', $id, $extension);

        if ($this->multimediaStorage->has($filename)) {
            throw new \LogicException(sprintf('File %s already exists.', $filename));
        }
        $content = file_get_contents($file->getRealPath());
        $this->multimediaStorage->write($filename, $content);

        $multimedia = new Multimedia(
            $id,
            $originalName,
            $extension,
            $this->multimediaStorage->getSize($filename),
            $hash,
            $this->multimediaStorage->getMimetype($filename)
        );

        $this->repository->save($multimedia);
    }
}
