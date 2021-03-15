<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Model;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;

class BatchActionInformationModel
{
    private BatchActionId $id;

    private BatchActionType $type;

    private int $processedEntries;

    private int $allEntries;

    private \DateTime $createdAt;

    private ?\DateTime $endedAt;

    /**
     * @var BatchActionEntryModel[]
     */
    private array $entries = [];

    public function __construct(
        BatchActionId $id,
        BatchActionType $type,
        int $allEntries,
        int $processedEntries,
        \DateTime $createdAt,
        ?\DateTime $endedAt
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->processedEntries = $processedEntries;
        $this->allEntries = $allEntries;
        $this->createdAt = $createdAt;
        $this->endedAt = $endedAt;
    }

    public function addEntry(BatchActionEntryModel $entry): void
    {
        $this->entries[] = $entry;
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getType(): BatchActionType
    {
        return $this->type;
    }

    public function getProcessedEntries(): int
    {
        return $this->processedEntries;
    }

    public function getAllEntries(): int
    {
        return $this->allEntries;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getEndedAt(): ?\DateTime
    {
        return $this->endedAt;
    }

    /**
     * @return BatchActionEntryModel[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }
}
