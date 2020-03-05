<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Webmozart\Assert\Assert;

/**
 */
class StatusProductRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $query;

    /**
     * @var StatusRepositoryInterface
     */
    private StatusRepositoryInterface $repository;

    /**
     * @param ProductQueryInterface     $query
     * @param StatusRepositoryInterface $repository
     */
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
    public function getRelationships(AggregateId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, StatusId::class);
        }

        $status = $this->repository->load($id);
        Assert::notNull($status);

        $attributeId = AttributeId::fromKey((new AttributeCode(StatusSystemAttribute::CODE))->getValue());
        /** @var Uuid $valueId */
        $valueId = Uuid::uuid5(ValueInterface::NAMESPACE, implode('|', [$status->getCode()->getValue(), null]));

        return $this->query->findProductIdByAttributeId($attributeId, $valueId);
    }
}
