<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Exception;

class Shopware6InstanceOfException extends Shopware6ExporterException
{
    private const MESSAGE = 'Expected an instance of {expected}.';

    public function __construct(string $expected, \Throwable $previous = null)
    {
        parent::__construct(
            self::MESSAGE,
            [
                '{expected}' => $expected,
            ],
            $previous
        );
    }
}
