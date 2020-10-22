<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Exception;

use Ergonode\Product\Domain\ValueObject\Sku;

class ImportRelatedProductIncorrectTypeException extends ImportException
{
    private const MESSAGE  = 'Incorrect related product type {{type}} to {{sku}}';

    /**
     * @param Sku             $from
     * @param string          $type
     * @param \Throwable|null $previous
     */
    public function __construct(Sku $from, string $type, \Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, ['sku' => $from->getValue(), 'type' => $type], $previous);
    }
}
