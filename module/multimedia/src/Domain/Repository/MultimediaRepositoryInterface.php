<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Repository;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;

/**
 */
interface MultimediaRepositoryInterface
{
    /**
     * @param MultimediaId $id
     *
     * @return Multimedia|null
     */
    public function load(MultimediaId $id): ?Multimedia;

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
     * @param MultimediaId $id
     */
    public function remove(MultimediaId $id): void;
}
