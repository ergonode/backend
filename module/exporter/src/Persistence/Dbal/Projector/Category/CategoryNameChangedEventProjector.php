<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Category;

use Ergonode\Category\Domain\Event\CategoryNameChangedEvent;
use Ergonode\Exporter\Domain\Exception\CategoryNotFoundException;
use Ergonode\Exporter\Domain\Repository\CategoryRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class CategoryNameChangedEventProjector
{
    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $repository;

    /**
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CategoryNameChangedEvent $event
     *
     * @throws CategoryNotFoundException
     */
    public function __invoke(CategoryNameChangedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $category = $this->repository->load($id);
        if (null === $category) {
            throw new CategoryNotFoundException($event->getAggregateId()->getValue());
        }

        $category->changeName($event->getTo());

        $this->repository->save($category);
    }
}
