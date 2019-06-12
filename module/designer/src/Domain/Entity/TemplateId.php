<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class TemplateId extends AbstractId
{
    public const NAMESPACE = '155fc493-1938-49e3-bfb7-393e11f6ee34';

    /**
     * @param string $value
     *
     * @return TemplateId
     * @throws \Exception
     */
    public static function fromKey(string $value): TemplateId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $value)->toString());
    }
}
