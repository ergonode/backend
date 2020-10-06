<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Throwable;

/**
 */
final class MultimediaImportAction
{
    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $repository;

    /**
     * @param MultimediaRepositoryInterface $repository
     */
    public function __construct(MultimediaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function action(
        ImportId $importId,
        MultimediaId $id,
        string $name,
        string $extension,
        int $size
    ): void {
        $multimedia = $this->repository->load($id);

        if (null === $multimedia) {
            try {
                $multimedia = new Multimedia(
                    $id,
                    $name,
                    $extension,
                    $size,
                    $hash,
                    $mime,
                );

                $this->repository->save($multimedia);
            } catch (Throwable $exception) {
                throw new ImportException($exception->getMessage(), [], $exception);
            }
        }
    }
}