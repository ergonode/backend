<?php
/*
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class ActiveImportRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Import is active %relations%';

    private ImportRepositoryInterface $importRepository;

    public function __construct(ImportRepositoryInterface $importRepository)
    {
        $this->importRepository = $importRepository;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof ImportId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ImportId::class);

        $import = $this->importRepository->load($id);
        if ($import && null === $import->getEndedAt()) {
            return new RelationshipGroup(self::MESSAGE, [$import->getId()]);
        }

        return new RelationshipGroup(self::MESSAGE, []);
    }
}
