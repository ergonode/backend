<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Entity;

use Ergonode\Account\Domain\ValueObject\Email;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class UserId extends AbstractId
{
    public const NAMESPACE = 'eb5fa5eb-ecda-4ff6-ac91-9ac817062635';

    /**
     * @param Email $value
     *
     * @return UserId
     *
     * @throws \Exception
     */
    public static function fromEmail(Email $value): UserId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $value->getValue())->toString());
    }
}
