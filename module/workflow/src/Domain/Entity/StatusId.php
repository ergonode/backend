<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ramsey\Uuid\Uuid;

/**
 */
class StatusId extends AbstractId
{
    public const NAMESPACE = 'dcf14212-d63d-4829-b914-71e3d5599ad2';

    /**
     * @param StatusCode $code
     *
     * @return StatusId
     *
     * @throws \Exception
     */
    public static function fromCode(StatusCode $code): StatusId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $code)->toString());
    }
}
