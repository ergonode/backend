<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Projector\CategoryTree;

use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoriesChangedEvent;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportTree;
use Ergonode\Exporter\Domain\Repository\TreeRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalCategoryTreeCategoriesChangedEventProjector
{
    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $repository;

    /**
     * @param TreeRepositoryInterface $repository
     */
    public function __construct(TreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CategoryTreeCategoriesChangedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(CategoryTreeCategoriesChangedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $tree = new ExportTree(
            $id,
            $event->getCategories()
        );

        $this->repository->save($tree);
    }
}
