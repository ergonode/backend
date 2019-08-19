<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Core\Infrastructure\JMS\Serializer\Handler\AbstractInterfaceHandler;

/**
 */
class OptionInterfaceHandler extends AbstractInterfaceHandler
{
    /**
     * {@inheritDoc}
     */
    public static function getSupportedInterface(): string
    {
        return OptionInterface::class;
    }
}
