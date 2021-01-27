<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Command;

use Ergonode\BatchAction\Application\Form\Model\BatchActionPayloadModel;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;

class CreateBatchActionCommand implements DomainCommandInterface
{
    private BatchActionId $id;

    private BatchActionType $type;

    private ?BatchActionFilter $filter;

    /**
     * @var BatchActionPayloadModel[]|null
     */
    private ?array $payload;

    /**
     * @param BatchActionPayloadModel[]|null $payload
     */
    public function __construct(
        BatchActionId $id,
        BatchActionType $type,
        ?BatchActionFilter $filter = null,
        ?array $payload = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->filter = $filter;
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

    public function getFilter(): ?BatchActionFilter
    {
        return $this->filter;
    }

    /**
     * @return BatchActionPayloadModel[]|null
     */
    public function getPayload(): ?array
    {
        return $this->payload;
    }
}
