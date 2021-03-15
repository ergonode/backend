<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Core\Infrastructure\JMS\Serializer\Handler\AbstractInterfaceHandler;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class TemplateElementInterfaceHandler extends AbstractInterfaceHandler
{
    public static function getSupportedInterface(): string
    {
        return TemplateElementInterface::class;
    }
}
