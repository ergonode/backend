<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;

interface MultimediaRepositoryInterface
{
    public function load(MultimediaId $id): ?AbstractMultimedia;

    public function save(AbstractMultimedia $multimedia): void;

    public function exists(MultimediaId $id): bool;

    public function delete(AbstractMultimedia $id): void;
}
