<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;
use Ergonode\Product\Domain\ValueObject\Sku;

class Shopware6ExporterNumericAttributeException extends Shopware6ExporterException
{
    private const MESSAGE = 'Attribute {code} value "{value}" is not numeric, required for product {sku}';

    public function __construct(AttributeCode $code, Sku $sku, string $value, \Throwable $previous = null)
    {
        parent::__construct(
            self::MESSAGE,
            [
                '{code}' => $code->getValue(),
                '{sku}' => $sku->getValue(),
                '{value}' => $value,
            ],
            $previous
        );
    }
}
