<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Builder\LanguageTree;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ramsey\Uuid\Uuid;

/**
 */
class NestedSetTree
{
    /**
     * @var Branch[]
     */
    private array $data = [];

    /**
     * @return Branch[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param AggregateId $id
     * @param string      $code
     */
    public function addRoot(AggregateId $id, string $code)
    {
        $this->data[] = new Branch(
            $id,
            $code,
            1,
            2
        );
    }

    /**
     * @param AggregateId $id
     * @param string      $code
     * @param AggregateId $parentId
     *
     * @throws \Exception
     */
    public function addNode(AggregateId $id, string $code, AggregateId $parentId)
    {
        $parentData = $this->findParent($parentId);
        $child = $this->findChildrenMaxLevel($parentId);

        if ($child) {
            $this->addChild($id, $code, $child);
        } else {
            $this->add($id, $code, $parentData);
        }
    }

    /**
     * @param AggregateId $id
     * @param string      $code
     * @param Branch      $child
     */
    private function addChild(AggregateId $id, string $code, Branch $child)
    {
        $right = $child->getRight();
        $this->updateLeftRight($child->getRight(), $child->getRight() + 1);

        $this->data[] = new Branch(
            $id,
            $code,
            $right + 1,
            $right + 2,
            $child->getParentId()
        );
    }

    /**
     * @param AggregateId $id
     * @param string      $code
     * @param Branch      $parent
     */
    private function add(AggregateId $id, string $code, Branch $parent)
    {
        $right = $parent->getRight();
        $this->updateLeftRight($parent->getLeft(), $parent->getRight());

        $this->data[] = new Branch(
            $id,
            $code,
            $right,
            $right + 1,
            $parent->getId()
        );
    }

    /**
     * @param int $left
     * @param int $right
     */
    private function updateLeftRight(int $left, int $right): void
    {
        foreach ($this->data as $row) {
            if ($row->getLeft() > $left) {
                $row->addToLeft(2);
            }
            if ($row->getRight() >= $right) {
                $row->addToRight(2);
            }
        }
    }

    /**
     * @param AggregateId $id
     *
     * @return Branch|null
     */
    private function findParent(AggregateId $id): ?Branch
    {
        foreach ($this->data as $row) {
            if ($row->isEqual($id)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @param AggregateId $id
     *
     * @return Branch|null
     */
    private function findChildrenMaxLevel(AggregateId $id): ?Branch
    {
        $child = null;
        foreach ($this->data as $row) {
            if ($row->getParentId() === $id) {
                if (null === $child) {
                    $child = $row;
                } else {
                    if ($child->getLeft() < $row->getLeft()) {
                        $child = $row;
                    }
                }
            }
        }

        return $child;
    }
}
