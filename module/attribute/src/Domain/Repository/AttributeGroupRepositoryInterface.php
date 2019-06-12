<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Attribute\Domain\Repository;

use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\Attribute\Domain\Entity\AttributeGroupId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

/**
 */
interface AttributeGroupRepositoryInterface
{
    /**
     * @param AttributeGroupId $id
     *
     * @return AbstractAggregateRoot|AttributeGroup
     *
     * @throws \ReflectionException
     */
    public function load(AttributeGroupId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;
}
