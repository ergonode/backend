<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Editor\Domain\Query\DraftQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class ProductDraftAttributeRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var DraftQueryInterface
     */
    private DraftQueryInterface $query;

    /**
     * @param DraftQueryInterface $query
     */
    public function __construct(DraftQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof AttributeId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationships(AggregateId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, AttributeId::class);
        }

        return $this->query->getNotAppliedWithAttribute($id);
    }
}
