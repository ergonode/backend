<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Repository;

use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

interface AttributeGroupRepositoryInterface
{
    /**
     * @return AbstractAggregateRoot|AttributeGroup
     *
     * @throws \ReflectionException
     */
    public function load(AttributeGroupId $id): ?AbstractAggregateRoot;

    public function save(AbstractAggregateRoot $aggregateRoot): void;

    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
