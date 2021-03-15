<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Repository;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

interface AttributeRepositoryInterface
{
    public function load(AttributeId $id): ?AbstractAttribute;

    public function save(AbstractAttribute $aggregateRoot): void;

    public function delete(AbstractAttribute $aggregateRoot): void;
}
