<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Editor\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Editor\Domain\Entity\ProductDraftId;

/**
 */
interface ProductDraftRepositoryInterface
{
    /**
     * @param ProductDraftId $id
     *
     * @param bool           $draft
     *
     * @return AbstractAggregateRoot
     */
    public function load(ProductDraftId $id, bool $draft = false): AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;
}
