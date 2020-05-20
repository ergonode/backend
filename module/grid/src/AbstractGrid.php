<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
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

    /**
     * @var string|null
     */
    private ?string $field = null;

    /**
     * @var string
     */
    private string $order = 'ASC';

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    abstract public function init(GridConfigurationInterface $configuration, Language $language): void;

    /**
     * @param string          $id
     * @param ColumnInterface $column
     */
    public function addColumn(string $id, ColumnInterface $column): void
    {
        $this->columns[$id] = $column;
    }

    /**
     * @param string $field
     * @param string $order
     */
    public function orderBy(string $field, string $order): void
    {
        $this->field = $field;
        $this->order = $order;
    }


    /**
     * @param string          $name
     * @param ActionInterface $action
     */
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

    /**
     * @return string|null
     */
    public function getField(): ?string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }
}
