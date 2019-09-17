<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Resolver;

use Ergonode\Core\Domain\Entity\AbstractId;

/**
 */
interface RelationResolverInterface
{
    /**
     * @param AbstractId $id
     *
     * @return array
     */
    public function resolve(AbstractId $id): array;
}
