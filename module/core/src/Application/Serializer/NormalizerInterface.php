<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer;

use Ergonode\Core\Application\Exception\DenoralizationException;
use Ergonode\Core\Application\Exception\NormalizationException;

interface NormalizerInterface
{
    /**
     * @throws NormalizationException
     */
    public function normalize(object $data, ?string $type): array;

    /**
     * @throws DenoralizationException
     */
    public function denormalize(array $data, string $type): object;
}
