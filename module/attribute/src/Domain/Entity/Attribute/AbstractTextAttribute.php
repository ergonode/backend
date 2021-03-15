<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

abstract class AbstractTextAttribute extends AbstractAttribute
{
    public const TYPE = 'TEXT';

    public function getType(): string
    {
        return self::TYPE;
    }
}
