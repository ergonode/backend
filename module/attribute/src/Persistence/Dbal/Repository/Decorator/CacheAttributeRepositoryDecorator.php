<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Repository\Decorator;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

/**
 */
class CacheAttributeRepositoryDecorator implements AttributeRepositoryInterface
{
    public const KEY = 'aggregate_attribute_%s';

    /**
     * @var AttributeRepositoryInterface
     */
    private $repository;

    /**
     * @var array
     */
    private $cache;

    /**
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AttributeId $id
     *
     * @return AbstractAggregateRoot|null
     */
    public function load(AttributeId $id): ?AbstractAggregateRoot
    {
        $key = $id->getValue();
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->repository->load($id);
        }

        return $this->cache[$key];
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->repository->save($aggregateRoot);
        $this->cache[$aggregateRoot->getId()->getValue()] = $aggregateRoot;
    }
}
