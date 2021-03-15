<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\ColumnInterface;

class ImageAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ImageAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        return new ImageColumn(
            $attribute->getCode()->getValue(),
            $attribute->getLabel()->get($language)
        );
    }
}
