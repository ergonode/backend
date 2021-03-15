<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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

    private ?string $field = null;

    private string $order = 'ASC';

    public function addColumn(string $id, ColumnInterface $column): self
    {
        $this->columns[$id] = $column;

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

    public function getField(): ?string
    {
        return $this->field;
    }

    public function getOrder(): string
    {
        return $this->order;
    }
}
