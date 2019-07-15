<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class TransformerId extends AbstractId
{
    public const NAMESPACE = '9bbd658e-f383-4af3-8e07-308bf3375827';

    /**
     * @param string $value
     *
     * @return TransformerId
     *
     * @throws \Exception
     */
    public static function fromKey(string $value): TransformerId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $value)->toString());
    }
}
