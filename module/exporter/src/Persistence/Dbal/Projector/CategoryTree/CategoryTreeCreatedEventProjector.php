<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\CategoryTree;

use Ergonode\Category\Domain\Event\Tree\CategoryTreeCreatedEvent;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportTree;
use Ergonode\Exporter\Domain\Repository\TreeRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class CategoryTreeCreatedEventProjector
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
     * @param CategoryTreeCreatedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(CategoryTreeCreatedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());

        $tree = new ExportTree(
            $id,
            []
        );


        $this->repository->save($tree);
    }
}
