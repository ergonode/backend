<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column;

use Ergonode\Grid\Filter\InFilter;

class IdColumn extends TextColumn
{
    public const TYPE = 'ID';

    public function __construct(string $field = 'id', string $label = 'Id')
    {
        parent::__construct($field, $label, new InFilter());

        $this->visible = false;
    }
}
