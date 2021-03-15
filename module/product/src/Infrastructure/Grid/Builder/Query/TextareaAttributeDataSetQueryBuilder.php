<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Builder\Query;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;

class TextareaAttributeDataSetQueryBuilder extends AbstractAttributeDataSetBuilder
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof TextareaAttribute;
    }
}
