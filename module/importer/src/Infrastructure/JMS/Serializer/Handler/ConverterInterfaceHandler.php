<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Core\Infrastructure\JMS\Serializer\Handler\AbstractInterfaceHandler;
use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;

class ConverterInterfaceHandler extends AbstractInterfaceHandler
{
    /**
     * {@inheritDoc}
     */
    public static function getSupportedInterface(): string
    {
        return ConverterInterface::class;
    }
}
