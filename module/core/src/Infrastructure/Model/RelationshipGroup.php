<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Model;

use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class RelationshipGroup
{
    private string $message;

    /**
     * @var AggregateId[]
     */
    private array $relations;

    /**
     * @param AggregateId[] $relations
     */
    public function __construct(string $message, array $relations)
    {
        Assert::notEmpty($message);
        Assert::allIsInstanceOf($relations, AggregateId::class);

        $this->message = $message;
        $this->relations = $relations;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return AggregateId[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }
}
