<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Mapper;

interface ExceptionMapperInterface
{
    /**
     * @return array|null
     */
    public function map(\Throwable $exception): ?array;
}
