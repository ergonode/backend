<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Repository;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
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
    public function load(MultimediaId $id): ?AbstractAggregateRoot;

    /**
     * @param Multimedia $multimedia
     */
    public function save(Multimedia $multimedia): void;

    /**
     * @param AbstractId $id
     *
     * @return bool
     */
    public function exists(AbstractId $id): bool;

    /**
     * @param Multimedia $id
     */
    public function delete(Multimedia $id): void;
}
