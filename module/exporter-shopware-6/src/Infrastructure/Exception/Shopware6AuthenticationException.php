<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Exception;

class Shopware6AuthenticationException extends Shopware6ExporterException
{
    private const MESSAGE = 'Invalid Credentials/Unauthorized access';

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            self::MESSAGE,
            [],
            $previous
        );
    }
}
