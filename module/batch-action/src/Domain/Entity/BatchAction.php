<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Entity;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionStatus;

class BatchAction
{
    private BatchActionId $id;

    private BatchActionType $type;

    private bool $autoEndOnErrors;

    private BatchActionStatus $status;

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
        $this->status = new BatchActionStatus(BatchActionStatus::PRECESSED);
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getStatus(): BatchActionStatus
    {
        return $this->status;
    }

    public function setStatus(BatchActionStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @param mixed|null $payload
     */
    public function setPayload($payload = null): void
    {
        $this->payload = $payload;
    }

    public function setAutoEndOnErrors(bool $autoEndOnErrors): void
    {
        $this->autoEndOnErrors = $autoEndOnErrors;
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
