<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Domain\Repository;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
interface AttributeRepositoryInterface
{
    /**
     * @param AttributeId $id
     *
     * @return AbstractAttribute
     */
    public function load(AttributeId $id): ?AbstractAttribute;

    /**
     * @param AbstractAttribute $aggregateRoot
     */
    public function save(AbstractAttribute $aggregateRoot): void;

    /**
     * @param AbstractAttribute $aggregateRoot
     */
    public function delete(AbstractAttribute $aggregateRoot): void;
}
