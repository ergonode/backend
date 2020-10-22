<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Core\Infrastructure\JMS\Serializer\Handler\AbstractInterfaceHandler;

class ConditionInterfaceHandler extends AbstractInterfaceHandler
{
    /**
     * {@inheritDoc}
     */
    public static function getSupportedInterface(): string
    {
        return ConditionInterface::class;
    }
}
