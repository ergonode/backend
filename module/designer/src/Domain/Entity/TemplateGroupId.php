<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class TemplateGroupId extends AbstractId
{
    public const NAMESPACE = 'fd811a92-55aa-4d86-97e8-031ed53637db';

    /**
     * @param string $value
     *
     * @return TemplateGroupId
     *
     * @throws \Exception
     */
    public static function fromKey(string $value): TemplateGroupId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $value)->toString());
    }
}
