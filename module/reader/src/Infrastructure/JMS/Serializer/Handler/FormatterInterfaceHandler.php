<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Core\Infrastructure\JMS\Serializer\Handler\AbstractInterfaceHandler;
use Ergonode\Reader\Infrastructure\FormatterInterface;

/**
 */
class FormatterInterfaceHandler extends AbstractInterfaceHandler
{
    /**
     * {@inheritDoc}
     */
    public static function getSupportedInterface(): string
    {
        return FormatterInterface::class;
    }
}
