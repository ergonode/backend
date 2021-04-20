<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractCollectionAttribute;

class ProductRelationAttribute extends AbstractCollectionAttribute
{
    public const TYPE = 'PRODUCT-RELATION';

    public function getType(): string
    {
        return self::TYPE;
    }
}
