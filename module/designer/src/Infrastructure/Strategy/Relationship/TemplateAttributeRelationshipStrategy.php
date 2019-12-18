<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Strategy\Relationship;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class TemplateAttributeRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var TemplateQueryInterface
     */
    private $query;

    /**
     * @param TemplateQueryInterface $query
     */
    public function __construct(TemplateQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractId $id): bool
    {
        return $id instanceof AttributeId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationships(AbstractId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, AttributeId::class);
        }

        return $this->query->findTemplateIdByAttributeId($id);
    }
}
