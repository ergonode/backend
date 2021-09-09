<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column;

use Ergonode\Grid\Column\AbstractColumn;
use Ergonode\Product\Infrastructure\Grid\Filter\RelationFilter;

class ProductRelationColumn extends AbstractColumn
{
    public const TYPE = 'PRODUCT_RELATION';

    public function __construct(string $field, ?string $label = null)
    {
        parent::__construct($field, $label, new RelationFilter());
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
