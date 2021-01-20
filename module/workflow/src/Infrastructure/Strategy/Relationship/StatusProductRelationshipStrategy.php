<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Strategy\Relationship;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class StatusProductRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with product %relations%';

    private ProductQueryInterface $query;

    private StatusRepositoryInterface $repository;

    public function __construct(ProductQueryInterface $query, StatusRepositoryInterface $repository)
    {
        $this->query = $query;
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof StatusId;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, StatusId::class);

        $status = $this->repository->load($id);
        Assert::notNull($status);

        $attributeId = AttributeId::fromKey((new AttributeCode(StatusSystemAttribute::CODE))->getValue());
        /** @var Uuid $valueId */
        $valueId = Uuid::uuid5(ValueInterface::NAMESPACE, implode('|', [$status->getCode()->getValue(), null]));

        $relations = $this->query->findProductIdByAttributeId($attributeId, $valueId);

        return new RelationshipGroup(self::MESSAGE, $relations);
    }
}
