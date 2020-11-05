<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Command;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

class CreateBatchActionCommand implements DomainCommandInterface
{
    private BatchActionId $id;

    private BatchActionType $type;

    /**
     * @var AggregateId[]
     */
    private array $ids;

    /**
     * @param AggregateId[] $ids
     */
    public function __construct(BatchActionId $id, BatchActionType $type, array $ids)
    {
        Assert::allIsInstanceOf($ids, AggregateId::class);
        Assert::minCount($ids, 1);

        $this->id = $id;
        $this->type = $type;
        $this->ids = $ids;
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
     * @return AggregateId[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }
}
