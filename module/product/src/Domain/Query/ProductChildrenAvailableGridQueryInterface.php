<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Doctrine\DBAL\Query\QueryBuilder;

interface ProductChildrenAvailableGridQueryInterface
{
    /**
     * @param AbstractAttribute[] $bindings
     */
    public function getGridQuery(AbstractAssociatedProduct $product, Language $language, array $bindings): QueryBuilder;
}
