<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Projector\Category;

use Ergonode\Category\Domain\Event\CategoryCreatedEvent;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ergonode\Exporter\Domain\Repository\CategoryRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalCategoryCreatedEventProjector
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
     * @param CategoryCreatedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(CategoryCreatedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $category = new ExportCategory(
            $id,
            $event->getCode()->getValue(),
            $event->getName()
        );

        $this->repository->save($category);
    }
}
