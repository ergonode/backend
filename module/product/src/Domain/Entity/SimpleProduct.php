<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Entity;

use JMS\Serializer\Annotation as JMS;

class SimpleProduct extends AbstractProduct
{
    public const TYPE = 'SIMPLE-PRODUCT';

    /**
     * @JMS\Type("string");
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
