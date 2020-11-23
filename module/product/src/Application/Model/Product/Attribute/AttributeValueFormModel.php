<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model\Product\Attribute;


class AttributeValueFormModel
{
    private ?string $id = null;

    /**
     * @var AttributeValueTranslationFormModel[]
     */
    private array $values = [];
}