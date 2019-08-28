<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Repository\Decorator;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;

/**
 */
class CacheTemplateRepositoryDecorator implements TemplateRepositoryInterface
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $repository;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param TemplateRepositoryInterface $repository
     */
    public function __construct(TemplateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param TemplateId $id
     *
     * @return AbstractAggregateRoot|null
     */
    public function load(TemplateId $id): ?AbstractAggregateRoot
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
