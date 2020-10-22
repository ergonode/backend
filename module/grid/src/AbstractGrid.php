<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Ergonode\Core\Domain\ValueObject\Language;

abstract class AbstractGrid
{
    /**
     * @var ColumnInterface[]
     */
    protected array $columns = [];

    /**
     * @var ActionInterface[]
     */
    private array $actions = [];

    private ?string $field = null;

    private string $order = 'ASC';

    abstract public function init(GridConfigurationInterface $configuration, Language $language): void;

    public function addColumn(string $id, ColumnInterface $column): void
    {
        $this->columns[$id] = $column;
    }

    public function orderBy(string $field, string $order): void
    {
        $this->field = $field;
        $this->order = $order;
    }


    public function addAction(string $name, ActionInterface $action): void
    {
        $this->actions[$name] = $action;
    }

    /**
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
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
