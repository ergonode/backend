<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer;

interface NormalizerInterface
{
    public function normalize(object $data, ?string $type): array;

    public function denormalize(array $data, string $type): object;
}
