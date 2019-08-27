<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Request\FilterCollection;

/**
 */
interface AttributeColumnStrategyInterface
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool;

    /**
     * @param AbstractAttribute $attribute
     * @param Language          $language
     * @param FilterCollection  $filter
     *
     * @return ColumnInterface
     */
    public function create(AbstractAttribute $attribute, Language $language, FilterCollection $filter): ColumnInterface;
}
