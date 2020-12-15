<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper;

use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class Shopware6ExporterProductNoFoundException extends Shopware6ExporterException
{
    private const MESSAGE = 'No found in shopware product {product_id}';

    public function __construct(ProductId $productId, \Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, ['{product_id}' => $productId->getValue()], $previous);
    }
}
