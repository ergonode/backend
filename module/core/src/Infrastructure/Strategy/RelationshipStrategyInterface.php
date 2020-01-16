<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Strategy;

use Ergonode\Core\Domain\Entity\AbstractId;

/**
 */
interface RelationshipStrategyInterface
{
    /**
     * @param AbstractId $id
     *
     * @return bool
     */
    public function supports(AbstractId $id): bool;

    /**
     * @param AbstractId $id
     *
     * @return array
     */
    public function getRelationships(AbstractId $id): array;
}
