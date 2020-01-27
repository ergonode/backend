<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Domain\Query\ProcessorQueryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class ProcessorTransformerRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var ProcessorQueryInterface
     */
    private ProcessorQueryInterface $query;

    /**
     * @param ProcessorQueryInterface $query
     */
    public function __construct(ProcessorQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractId $id): bool
    {
        return $id instanceof TransformerId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationships(AbstractId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, TransformerId::class);
        }

        return $this->query->findProcessorIdByTransformerId($id);
    }
}
