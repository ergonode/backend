<?php
/**
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
    private const ONE_MESSAGE = 'Source has one active import';
    private const MULTIPLE_MESSAGE = 'Source has %count% active imports';

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

        $relations = $this->importQuery->findActiveImport($id);
        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
