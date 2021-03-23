<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\PropertyGroup;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\AbstractShopware6ProductPropertyGroupOptionMapper;

class ProductGroupMultiSelectMapper extends AbstractShopware6ProductPropertyGroupOptionMapper
{
    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return MultiSelectAttribute::TYPE;
    }
}
