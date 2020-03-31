<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\CategoryTree;

use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoryAddedEvent;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportTree;
use Ergonode\Exporter\Domain\Repository\TreeRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ramsey\Uuid\Uuid;

/**
 */
class CategoryTreeCategoryAddedEventProjector
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
     * @param CategoryTreeCategoryAddedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(CategoryTreeCategoryAddedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());

        $tree = $this->repository->load($id);
        $categories = $tree->getCategories();


        $parent = $event->getParentId() ? $this->findNode($categories, $event->getParentId()) : null;
        $node = new Node($event->getCategoryId());

        if ($parent) {
            $parent->addChildren($node);
        } else {
            $categories[] = $node;
        }

        $newTree = new ExportTree(
            $id,
            $categories
        );

        $this->repository->save($newTree);
    }

    /**
     * @param array      $categories
     * @param CategoryId $categoryId
     *
     * @return Node|null
     */
    private function findNode(array $categories, CategoryId $categoryId): ?Node
    {
        foreach ($categories as $category) {
            $node = $this->findSingleNode($categoryId, $category);
            if ($node) {
                return $node;
            }
        }

        return null;
    }
    /**
     * @param CategoryId $categoryId
     * @param Node       $node
     *
     * @return Node|null
     */
    private function findSingleNode(CategoryId $categoryId, Node $node): ?Node
    {
        if ($node->getCategoryId()->isEqual($categoryId)) {
            return $node;
        }

        foreach ($node->getChildrens() as $children) {
            $node = $this->findSingleNode($categoryId, $children);
            if ($node) {
                return $node;
            }
        }

        return null;
    }
}
