<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Entity;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionAction;

class BatchAction
{
    private BatchActionId $id;

    private BatchActionType $type;

    private BatchActionAction $action;

    public function __construct(BatchActionId $id, BatchActionType $type, BatchActionAction $action)
    {
        $this->id = $id;
        $this->type = $type;
        $this->action = $action;
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getType(): BatchActionType
    {
        return $this->type;
    }

    public function getAction(): BatchActionAction
    {
        return $this->action;
    }
}
