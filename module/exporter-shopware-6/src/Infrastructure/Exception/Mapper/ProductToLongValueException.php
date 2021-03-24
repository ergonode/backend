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

class ProductToLongValueException extends Shopware6ExporterException
{
    private const MESSAGE = 'Attribute {code} is too long max {length}, required for product {sku}';

    public function __construct(AttributeCode $code, Sku $sku, int $length, \Throwable $previous = null)
    {
        parent::__construct(
            self::MESSAGE,
            [
                '{code}' => $code->getValue(),
                '{sku}' => $sku->getValue(),
                '{length}' => $length,
            ],
            $previous
        );
    }
}
