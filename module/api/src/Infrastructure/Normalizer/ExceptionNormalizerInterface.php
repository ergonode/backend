<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Infrastructure\Normalizer;

interface ExceptionNormalizerInterface
{
    /**
     * @return array
     */
    public function normalize(\Exception $exception, ?string $code = null, ?string $message = null): array;
}
