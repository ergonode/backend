<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Strategy;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Strategy\RelationStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class TemplateProductRelationStrategy implements RelationStrategyInterface
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
    public function getRelations(AbstractId $id): array
    {
        if (!$this->supports($id)) {
            new UnexpectedTypeException($id, TemplateId::class);
        }

        return $this->query->findProductIdByTemplateId($id);
    }
}
