<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Serializer;

interface SerializerInterface
{
    /**
     * @param mixed $object
     */
    public function serialize($object): string;

    /**
     * @return mixed
     */
    public function deserialize(string $json, string $type);
}
