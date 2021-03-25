<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper;

use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class Shopware6ExporterProductNoFoundException extends Shopware6ExporterException
{
    private const MESSAGE = 'No found in shopware product {product_id}';

    public function __construct(ProductId $productId, Sku $sku = null, \Throwable $previous = null)
    {
        $value = $productId->getValue();
        if ($sku) {
            $value = $sku->getValue();
        }

        parent::__construct(self::MESSAGE, ['{product_id}' => $value], $previous);
    }
}
