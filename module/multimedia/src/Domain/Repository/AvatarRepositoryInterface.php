<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Multimedia\Domain\Entity\Avatar;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;

/**
 */
interface AvatarRepositoryInterface
{
    /**
     * @param AvatarId $id
     *
     * @return Avatar|null
     */
    public function load(AvatarId $id): ?AbstractAggregateRoot;

    /**
     * @param Avatar $avatar
     */
    public function save(Avatar $avatar): void;

    /**
     * @param AvatarId $id
     *
     * @return bool
     */
    public function exists(AvatarId $id): bool;

    /**
     * @param Avatar $id
     */
    public function delete(Avatar $id): void;
}
