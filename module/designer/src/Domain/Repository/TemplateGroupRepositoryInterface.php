<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Repository;

use Ergonode\Designer\Domain\Entity\TemplateGroup;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

interface TemplateGroupRepositoryInterface
{
    /**
     * @param TemplateGroupId $id
     *
     * @return TemplateGroup|null
     */
    public function load(TemplateGroupId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;
}
