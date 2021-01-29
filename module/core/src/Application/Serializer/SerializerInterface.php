<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer;

interface SerializerInterface
{
    public const FORMAT = 'json';

    /**
     * @param mixed $object
     */
    public function serialize($object, ?string $format = self::FORMAT): string;

    /**
     * @return mixed
     */
    public function deserialize(string $json, string $type, ?string $format = self::FORMAT);
}
