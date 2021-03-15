<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Entity;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;

class BatchAction
{
    private BatchActionId $id;

    private BatchActionType $type;

    public function __construct(BatchActionId $id, BatchActionType $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getType(): BatchActionType
    {
        return $this->type;
    }
}
