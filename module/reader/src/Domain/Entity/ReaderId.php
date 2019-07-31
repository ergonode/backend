<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class ReaderId extends AbstractId
{
    public const NAMESPACE = '1739dfef-6189-4fb1-b523-394d0395ef75';

    /**
     * @param string $value
     *
     * @return ReaderId
     *
     * @throws \Exception
     */
    public static function fromValue(string $value): ReaderId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $value)->toString());
    }
}
