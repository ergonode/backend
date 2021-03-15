<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Repository\Decorator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class CacheAttributeRepositoryDecorator implements AttributeRepositoryInterface
{
    public const KEY = 'aggregate_attribute_%s';

    private AttributeRepositoryInterface $repository;

    /**
     * @var array
     */
    private array $cache;

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
