<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Strategy\Relationship;

use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class ChannelActiveExportRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Channel has one active export';
    private const MULTIPLE_MESSAGE = 'Channel has %count% active exports';

    private ExportQueryInterface $query;

    public function __construct(ExportQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof ChannelId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ChannelId::class);

        $relations = $this->query->findActiveExport($id);
        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
