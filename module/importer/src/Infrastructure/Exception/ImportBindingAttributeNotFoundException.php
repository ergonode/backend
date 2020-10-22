<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Exception;

use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

class ImportBindingAttributeNotFoundException extends ImportException
{
    private const MESSAGE  = 'Can\'t find attribute {{code}} new to bind product {{sku}}';

    public function __construct(AttributeCode $code, Sku $sku, \Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, ['code' => $code->getValue(), 'sku' => $sku->getValue()], $previous);
    }
}
