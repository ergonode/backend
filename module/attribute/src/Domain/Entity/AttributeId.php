<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeId extends AbstractId
{
    public const NAMESPACE = 'eb5fa5eb-ecda-4ff6-ac91-9ac817062635';

    /**
     * @param AttributeCode $value
     *
     * @return AttributeId
     *
     * @throws \Exception
     */
    public static function fromKey(AttributeCode $value): AttributeId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $value->getValue())->toString());
    }
}
