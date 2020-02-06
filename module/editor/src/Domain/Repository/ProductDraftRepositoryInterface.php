<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Editor\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

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
