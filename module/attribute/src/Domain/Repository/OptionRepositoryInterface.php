<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
interface OptionRepositoryInterface
{
    /**
     * @param AggregateId $id
     *
     * @return AbstractAggregateRoot|AbstractOption
     */
    public function load(AggregateId $id): ?AbstractOption;

    /**
     * @param AbstractOption $option
     */
    public function save(AbstractOption $option): void;

    /**
     * @param AbstractOption $option
     */
    public function delete(AbstractOption $option): void;
}
