<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\Multimedia\Persistence\Dbal\Repository\Factory\MultimediaIdFactory;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

/**
 */
class MultimediaProvider
{
    /**
     * @var HashCalculationServiceInterface
     */
    private $hashService;

    /**
     * @var MultimediaRepositoryInterface
     */
    private $repository;

    /**
     * @var MultimediaIdFactory
     */
    private $multimediaIdFactory;

    /**
     * @param HashCalculationServiceInterface $hashService
     * @param MultimediaRepositoryInterface   $repository
     * @param MultimediaIdFactory             $multimediaIdFactory
     */
    public function __construct(
        HashCalculationServiceInterface $hashService,
        MultimediaRepositoryInterface $repository,
        MultimediaIdFactory $multimediaIdFactory
    ) {
        $this->hashService = $hashService;
        $this->repository = $repository;
        $this->multimediaIdFactory = $multimediaIdFactory;
    }

    /**
     * @param \SplFileInfo $file
     * @return Multimedia
     * @throws \Exception
     */
    public function provide(\SplFileInfo $file): Multimedia
    {
        $hash = $this->hashService->calculateHash($file);
        $multimediaId = $this->multimediaIdFactory->createFromFile($file);
        $multimedia = $this->repository->load($multimediaId);
        $guesser = MimeTypeGuesser::getInstance();
        $mimeType = $guesser->guess($file->getPathname());
        if (null === $multimedia) {
            $multimedia = new Multimedia(
                $multimediaId,
                $file->getFilename(),
                $file->getExtension(),
                $file->getSize(),
                $hash,
                $mimeType
            );
            $this->repository->save($multimedia);
        }

        return $multimedia;
    }
}
