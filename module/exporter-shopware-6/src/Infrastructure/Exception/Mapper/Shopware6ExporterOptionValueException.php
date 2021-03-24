<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;

class Shopware6ExporterOptionValueException extends Shopware6ExporterException
{
    private const MESSAGE = 'Option value not found, required for attribute {code}';

    public function __construct(AttributeCode $code, \Throwable $previous = null)
    {
        parent::__construct(
            self::MESSAGE,
            ['{code}' => $code->getValue()],
            $previous
        );
    }
}
