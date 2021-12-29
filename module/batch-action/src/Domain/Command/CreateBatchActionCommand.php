<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Command;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;

class CreateBatchActionCommand extends AbstractPayloadCommand implements DomainCommandInterface
{
    private BatchActionId $id;

    private BatchActionType $type;

    private BatchActionFilterInterface $filter;

    /**
     * @param mixed $payload
     */
    public function __construct(
        BatchActionId $id,
        BatchActionType $type,
        BatchActionFilterInterface $filter,
        $payload = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->filter = $filter;
        parent::__construct($payload);
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getType(): BatchActionType
    {
        return $this->type;
    }

    public function getFilter(): BatchActionFilterInterface
    {
        return $this->filter;
    }
}
