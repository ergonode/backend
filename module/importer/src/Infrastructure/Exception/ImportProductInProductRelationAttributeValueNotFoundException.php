<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Exception;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\ValueObject\Sku;

class ImportProductInProductRelationAttributeValueNotFoundException extends ImportException
{
    private const MESSAGE = 'Missing {sku} product for {attribute} relation attribute.';

    public function __construct(Sku $sku, AttributeCode $attribute, \Throwable $previous = null)
    {
        parent::__construct(
            self::MESSAGE,
            ['{sku}' => $sku->getValue(), '{attribute}' => $attribute->getValue()],
            $previous
        );
    }
}
