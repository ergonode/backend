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
     * @return Multimedia|null
     */
    public function load(MultimediaId $id): ?AbstractAggregateRoot;

    public function save(Multimedia $multimedia): void;

    public function exists(MultimediaId $id): bool;

    public function delete(Multimedia $id): void;
}
