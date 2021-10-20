<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use Ergonode\Importer\Domain\Factory\MultimediaFileFactory;

class MultimediaFromUrlImportAction
{
    private MultimediaRepositoryInterface $repository;

    private MultimediaQueryInterface $multimediaQuery;

    private MultimediaExtensionProvider $provider;

    private MultimediaFileFactory $factory;

    public function __construct(
        MultimediaRepositoryInterface $repository,
        MultimediaQueryInterface $multimediaQuery,
        MultimediaExtensionProvider $provider,
        MultimediaFileFactory $factory
    ) {
        $this->repository = $repository;
        $this->multimediaQuery = $multimediaQuery;
        $this->provider = $provider;
        $this->factory = $factory;
    }

    public function action(
        ImportId $importId,
        string $url,
        string $name,
        ?TranslatableString $alt = null
    ): MultimediaId {
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        if (!in_array($extension, $this->provider->dictionary(), true)) {
            throw new ImportException('Multimedia type {type} is not allowed ', ['{type}' => $extension]);
        }

        $multimediaId = $this->multimediaQuery->findIdByFilename($name);

        if ($multimediaId) {
            $multimedia = $this->repository->load($multimediaId);
        } else {
            $multimedia = $this->factory->create($name, $url);
        }

        if ($alt) {
            $multimedia->changeAlt($alt);
        }

        $this->repository->save($multimedia);

        return $multimedia->getId();
    }
}
