<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Entity;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;

class BatchAction
{
    private BatchActionId $id;

    private BatchActionType $type;

    /**
     * @var mixed
     */
    private $payload;

    /**
     * @param mixed $payload
     */
    public function __construct(BatchActionId $id, BatchActionType $type, $payload = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->payload = $payload;
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getType(): BatchActionType
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
