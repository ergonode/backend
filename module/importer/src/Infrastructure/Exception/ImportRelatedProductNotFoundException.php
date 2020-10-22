<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Exception;

use Ergonode\Product\Domain\ValueObject\Sku;

class ImportRelatedProductNotFoundException extends ImportException
{
    private const MESSAGE  = 'Cant\'t find related {{to}} product to {{from}}';

    /**
     * @param Sku             $from
     * @param Sku             $to
     * @param \Throwable|null $previous
     */
    public function __construct(Sku $from, Sku $to, \Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, ['from' => $from->getValue(), 'to' => $to->getValue()], $previous);
    }
}
