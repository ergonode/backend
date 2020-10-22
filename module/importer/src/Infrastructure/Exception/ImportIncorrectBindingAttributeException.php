<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Exception;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
class ImportIncorrectBindingAttributeException extends ImportException
{
    private const MESSAGE = 'Attribute {{code}} is not a select attribute, required for product {{sku}}';

    /**
     * @param AttributeCode   $code
     * @param Sku             $sku
     * @param \Throwable|null $previous
     */
    public function __construct(AttributeCode $code, Sku $sku, \Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, ['code' => $code->getValue(), 'sku' => $sku], $previous);
    }
}
