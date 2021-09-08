<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Entity;

class Category extends AbstractCategory implements CategoryInterface
{
    public const TYPE = 'DEFAULT';

    public function getType(): string
    {
        return self::TYPE;
    }
}
