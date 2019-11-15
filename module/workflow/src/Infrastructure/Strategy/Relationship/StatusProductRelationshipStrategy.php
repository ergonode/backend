<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Strategy\Relationship;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusAttribute;
use Ergonode\Workflow\Domain\Entity\StatusId;
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
    private $query;

    /**
     * @var StatusRepositoryInterface
     */
    private $repository;

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
    public function supports(AbstractId $id): bool
    {
        return $id instanceof StatusId;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function getRelationships(AbstractId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, StatusId::class);
        }

        $status = $this->repository->load($id);
        Assert::notNull($status);

        $attributeId = AttributeId::fromKey(new AttributeCode(StatusAttribute::CODE));
        /** @var Uuid $valueId */
        $valueId = Uuid::uuid5(ValueInterface::NAMESPACE, implode('|', [$status->getCode()->getValue(), null]));

        return $this->query->findProductIdByAttributeId($attributeId, $valueId);
    }
}
