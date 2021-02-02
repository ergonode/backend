<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class SourceActiveImportRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Source has active import %relations%';

    private ImportQueryInterface $importQuery;

    public function __construct(ImportQueryInterface $importQuery)
    {
        $this->importQuery = $importQuery;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof SourceId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, SourceId::class);

        return new RelationshipGroup(self::MESSAGE, $this->importQuery->findActiveImport($id));
    }
}
