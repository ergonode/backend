<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
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
