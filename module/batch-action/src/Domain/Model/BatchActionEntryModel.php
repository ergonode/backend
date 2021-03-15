<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Model;

use Ergonode\SharedKernel\Domain\AggregateId;

class BatchActionEntryModel
{
    private AggregateId $id;

    /**
     * @var string[]
     */
    private array $messages = [];

    public function __construct(AggregateId $id)
    {
        $this->id = $id;
    }

    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    public function getId(): AggregateId
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
