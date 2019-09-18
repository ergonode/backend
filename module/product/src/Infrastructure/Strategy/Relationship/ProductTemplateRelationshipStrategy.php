<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class ProductTemplateRelationshipStrategy implements RelationshipStrategyInterface
{
    public const TYPE = 'product';

    /**
     * @var ProductQueryInterface
     */
    private $query;

    /**
     * @param ProductQueryInterface $query
     */
    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractId $id): bool
    {
        return $id instanceof TemplateId;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationships(AbstractId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, TemplateId::class);
        }

        return $this->query->findProductIdByTemplateId($id);
    }
}
