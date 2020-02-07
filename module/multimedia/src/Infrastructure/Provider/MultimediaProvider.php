<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

/**
 */
class MultimediaProvider
{
    /**
     * @var HashCalculationServiceInterface
     */
    private HashCalculationServiceInterface $hashService;

    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $repository;

    /**
     * @param HashCalculationServiceInterface $hashService
     * @param MultimediaRepositoryInterface   $repository
     */
    public function __construct(
        HashCalculationServiceInterface $hashService,
        MultimediaRepositoryInterface $repository
    ) {
        $this->hashService = $hashService;
        $this->repository = $repository;
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return Multimedia
     *
     * @throws \Exception
     */
    public function provide(\SplFileInfo $file): Multimedia
    {
        $hash = $this->hashService->calculateHash($file);
        $multimediaId = MultimediaId::generate();
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
