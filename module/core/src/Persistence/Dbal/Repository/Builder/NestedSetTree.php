<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Repository\Builder;

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
     * @param string $code
     *
     * @throws \Exception
     */
    public function addRoot(string $code)
    {
        $id = Uuid::uuid4();
        $this->data[] = new Branch(
            $id,
            $code,
            1,
            2
        );
    }

    /**
     * @param string $code
     * @param string $parent
     *
     * @throws \Exception
     */
    public function addNode(string $code, string $parent)
    {
        $parentData = $this->findParent($parent);
        $child = $this->findChildrenMaxLevel($parent);


        if ($child) {
            $this->addChild($code, $child);
        } else {
            $this->add($code, $parentData);
        }
    }

    /**
     * @param string $code
     * @param Branch $child
     *
     * @throws \Exception
     */
    private function addChild(string $code, Branch $child)
    {
        $id = Uuid::uuid4();
        $right = $child->getRight();
        $this->updateLeftRight($child->getRight(), $child->getRight() + 1);

        $this->data[] = new Branch(
            $id,
            $code,
            $right+ 1,
            $right + 2,
            $child->getParentId(),
            $child->getParentCode()
        );
    }

    /**
     * @param string $code
     * @param Branch $parent
     *
     * @throws \Exception
     */
    private function add(string $code, Branch $parent)
    {
        $id = Uuid::uuid4();
        $right = $parent->getRight();
        $this->updateLeftRight($parent->getLeft(), $parent->getRight());

        $this->data[] = new Branch(
            $id,
            $code,
            $right,
            $right + 1,
            $parent->getId(),
            $parent->getCode()
        );
    }

    /**
     * @param $left
     * @param $right
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
     * @param string $id
     *
     * @return Branch|null
     */
    private function findParent(string $id): ?Branch
    {
        foreach ($this->data as $row) {
            if ($row->isEqualCode($id)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @param string $code
     *
     * @return Branch|null
     */
    private function findChildrenMaxLevel(string $code): ?Branch
    {
        $child = null;
        foreach ($this->data as $row) {
            if ($row->getParentCode() === $code) {
                if ($child === null) {
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
