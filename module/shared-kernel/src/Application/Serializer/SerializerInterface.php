<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Application\Serializer;

use Ergonode\SharedKernel\Application\Serializer\Exception\DeserializationException;
use Ergonode\SharedKernel\Application\Serializer\Exception\SerializationException;

interface SerializerInterface
{
    public const FORMAT = 'json';

    /**
     * @throws SerializationException
     *
     * @param mixed $data
     */
    public function serialize($data, ?string $format = self::FORMAT): string;

    /**
     * @throws DeserializationException
     *
     * @return mixed
     */
    public function deserialize(string $data, string $type, ?string $format = self::FORMAT);
}
