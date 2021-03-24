<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper;

use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;

class Shopware6ExporterNoMapperException extends Shopware6ExporterException
{
    private const MESSAGE = 'No mapped {field} value {value}';

    public function __construct(string $field, string $value, \Throwable $previous = null)
    {
        parent::__construct(
            self::MESSAGE,
            ['{field}' => $field, '{value}' => $value],
            $previous
        );
    }
}
