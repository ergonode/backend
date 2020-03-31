<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\Multimedia\Domain\Factory\MultimediaFactory;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\Multimedia\Infrastructure\Service\Upload\MultimediaUploadService;
use Symfony\Component\HttpFoundation\File\File;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaFileProviderInterface;

/**
 */
class AddMultimediaCommandHandler
{
    /**
     * @var MultimediaUploadService
     */
    private MultimediaUploadService $uploadService;

    /**
     * @var HashCalculationServiceInterface
     */
    private HashCalculationServiceInterface $hashService;

    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $repository;

    /**
     * @var MultimediaFactory
     */
    private MultimediaFactory $factory;

    /**
     * @var MultimediaFileProviderInterface
     */
    private MultimediaFileProviderInterface $provider;

    /**
     * @param MultimediaUploadService         $uploadService
     * @param HashCalculationServiceInterface $hashService
     * @param MultimediaRepositoryInterface   $repository
     * @param MultimediaFactory               $factory
     * @param MultimediaFileProviderInterface $provider
     */
    public function __construct(
        MultimediaUploadService $uploadService,
        HashCalculationServiceInterface $hashService,
        MultimediaRepositoryInterface $repository,
        MultimediaFactory $factory,
        MultimediaFileProviderInterface $provider
    ) {
        $this->uploadService = $uploadService;
        $this->hashService = $hashService;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->provider = $provider;
    }

    /**
     * @param AddMultimediaCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(AddMultimediaCommand $command)
    {
        $id = $command->getId();
        $file = $command->getFile();
        $hash = $this->hashService->calculateHash($file);
        $originalName = $file->getFilename();
        $filename = sprintf('%s.%s', $hash->getValue(), $file->getExtension());
        if (!$this->provider->hasFile($filename)) {
            $file = $this->uploadService->upload($id, $file, $hash);
        } else {
            $file = new File($this->provider->getFile($filename));
        }

        $multimedia = $this->factory->create($id, $originalName, $file, $hash);

        $this->repository->save($multimedia);
    }
}
