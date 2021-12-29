<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
    private const MESSAGE = 'Channel has active export %relations%';

    private ExportQueryInterface $exportQuery;

    public function __construct(ExportQueryInterface $exportQuery)
    {
        $this->exportQuery = $exportQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof ChannelId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ChannelId::class);

        return new RelationshipGroup(self::MESSAGE, $this->exportQuery->findActiveExport($id));
    }
}
