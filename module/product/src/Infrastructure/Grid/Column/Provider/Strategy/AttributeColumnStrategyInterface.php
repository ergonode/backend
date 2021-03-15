<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\ColumnInterface;

interface AttributeColumnStrategyInterface
{
    public function supports(AbstractAttribute $attribute): bool;

    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface;
}
