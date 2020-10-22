<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

interface MultimediaRepositoryInterface
{
    /**
     * @param MultimediaId $id
     *
     * @return Multimedia|null
     */
    public function load(MultimediaId $id): ?AbstractAggregateRoot;

    /**
     * @param Multimedia $multimedia
     */
    public function save(Multimedia $multimedia): void;

    /**
     * @param MultimediaId $id
     *
     * @return bool
     */
    public function exists(MultimediaId $id): bool;

    /**
     * @param Multimedia $id
     */
    public function delete(Multimedia $id): void;
}
