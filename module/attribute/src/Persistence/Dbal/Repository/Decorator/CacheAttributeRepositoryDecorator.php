<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Repository\Decorator;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

/**
 */
class CacheAttributeRepositoryDecorator implements AttributeRepositoryInterface
{
    public const KEY = 'aggregate_attribute_%s';

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var array
     */
    private array $cache;

    /**
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function load(AttributeId $id): ?AbstractAttribute
    {
        $key = $id->getValue();
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->repository->load($id);
        }

        return $this->cache[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function save(AbstractAttribute $aggregateRoot): void
    {
        $this->repository->save($aggregateRoot);
        $this->cache[$aggregateRoot->getId()->getValue()] = $aggregateRoot;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(AbstractAttribute $aggregateRoot): void
    {
        $this->repository->delete($aggregateRoot);
        unset($this->cache[$aggregateRoot->getId()->getValue()]);
    }
}
