<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractCollectionAttribute;

class ProductRelationAttribute extends AbstractCollectionAttribute
{
    public const MAX_RELATIONS = 100;
    public const TYPE = 'PRODUCT_RELATION';

    public function getType(): string
    {
        return self::TYPE;
    }
}
