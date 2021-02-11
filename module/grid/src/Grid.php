<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

class Grid implements GridInterface
{
    /**
     * @var ColumnInterface[]
     */
    private array $columns = [];

    /**
     * @var ActionInterface[]
     */
    private array $actions = [];

    private ?string $field = null;

    private string $order = 'ASC';

    public function addColumn(string $id, ColumnInterface $column): self
    {
        $this->columns[$id] = $column;

        return $this;
    }

    public function addAction(string $id, ActionInterface $action): self
    {
        $this->actions[$id] = $action;

        return $this;
    }

    public function orderBy(string $field, string $order): void
    {
        $this->field = $field;
        $this->order = $order;
    }

    /**
     * @return ColumnInterface[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return ActionInterface[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function getOrder(): string
    {
        return $this->order;
    }
}
