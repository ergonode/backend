<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Strategy\Relationship;

use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class ActiveExportRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Export is active %relations%';

    private ExportRepositoryInterface $exportRepository;

    public function __construct(ExportRepositoryInterface $exportRepository)
    {
        $this->exportRepository = $exportRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof ExportId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ExportId::class);

        $export = $this->exportRepository->load($id);
        if ($export && null === $export->getEndedAt()) {
            return new RelationshipGroup(self::MESSAGE, [$export->getId()]);
        }

        return new RelationshipGroup(self::MESSAGE, []);
    }
}
