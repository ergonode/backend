<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Core\Infrastructure\JMS\Serializer\Handler\AbstractInterfaceHandler;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;

/**
 */
class TemplateElementPropertyInterfaceHandler extends AbstractInterfaceHandler
{
    /**
     * {@inheritDoc}
     */
    public static function getSupportedInterface(): string
    {
        return TemplateElementPropertyInterface::class;
    }
}
