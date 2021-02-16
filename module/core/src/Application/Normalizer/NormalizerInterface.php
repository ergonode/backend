<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Normalizer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;

interface NormalizerInterface
{
    public function normalize(object $data, ?SerializationContext $context, ?string $type): array;

    public function denormalize(array $data, string $type, ?DeserializationContext $context): object;
}
