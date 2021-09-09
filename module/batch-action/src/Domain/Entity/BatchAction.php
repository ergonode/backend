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

    private bool $autoEndOnErrors;

    /**
     * @var mixed
     */
    private $payload;

    /**
     * @param mixed $payload
     */
    public function __construct(BatchActionId $id, BatchActionType $type, $payload = null, bool $autoEndOnErrors = true)
    {
        $this->id = $id;
        $this->type = $type;
        $this->payload = $payload;
        $this->autoEndOnErrors = $autoEndOnErrors;
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getType(): BatchActionType
    {
        return $this->type;
    }

    public function isAutoEndOnErrors(): bool
    {
        return $this->autoEndOnErrors;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
