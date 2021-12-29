<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\ValueObject;

class BatchActionFilterDisabled implements BatchActionFilterInterface
{
    public function getIds(): ?BatchActionIds
    {
        return null;
    }

    public function getQuery(): ?string
    {
        return null;
    }
}
