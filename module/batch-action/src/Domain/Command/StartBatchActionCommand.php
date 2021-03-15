<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Command;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;

class StartBatchActionCommand implements DomainCommandInterface
{
    private BatchActionId $id;

    private BatchActionFilterInterface $filter;

    /**
     * @var mixed
     */
    private $payload;

    /**
     * @param mixed $payload
     */
    public function __construct(
        BatchActionId $id,
        BatchActionFilterInterface $filter,
        $payload = null
    ) {
        $this->id = $id;
        $this->filter = $filter;
        $this->payload = $payload;
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getFilter(): BatchActionFilterInterface
    {
        return $this->filter;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
