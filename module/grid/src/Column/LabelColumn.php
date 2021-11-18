<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column;

class LabelColumn extends AbstractColumn
{
    public const TYPE = 'LABEL';

    public function getType(): string
    {
        return self::TYPE;
    }
}
