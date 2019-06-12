<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
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
    private $hashService;

    /**
     * @var MultimediaRepositoryInterface
     */
    private $repository;

    /**
     * @param HashCalculationServiceInterface $hashService
     * @param MultimediaRepositoryInterface   $repository
     */
    public function __construct(HashCalculationServiceInterface $hashService, MultimediaRepositoryInterface $repository)
    {
        $this->hashService = $hashService;
        $this->repository = $repository;
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return Multimedia
     */
    public function provide(\SplFileInfo $file): Multimedia
    {
        $hash = $this->hashService->calculateHash($file);
        $multimediaId = MultimediaId::createFromFile($file);
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
