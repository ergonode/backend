<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

class DeleteUnitCommand implements CoreCommandInterface
{
    private UnitId $id;

    public function __construct(UnitId $id)
    {
        $this->id = $id;
    }

    public function getId(): UnitId
    {
        return $this->id;
    }
}
