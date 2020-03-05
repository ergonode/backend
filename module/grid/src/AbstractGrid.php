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
    public const PARAMETER_ALLOW_COLUMN_RESIZE = 'allow_column_resize';
    public const PARAMETER_ALLOW_COLUMN_EDIT = 'allow_column_edit';
    public const PARAMETER_ALLOW_COLUMN_MOVE = 'allow_column_move';

    public const DEFAULT = [
        self::PARAMETER_ALLOW_COLUMN_RESIZE => false,
        self::PARAMETER_ALLOW_COLUMN_EDIT => false,
        self::PARAMETER_ALLOW_COLUMN_MOVE => false,
    ];

    /**
     * @var ColumnInterface[]
     */
    protected array $columns = [];

    /**
     * @var ActionInterface[]
     */
    private array $actions = [];

    /**
     * @var array
     */
    private array $configuration = [];

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
     * @param string      $name
     * @param string|bool $value
     */
    public function setConfiguration(string $name, $value): void
    {
        $this->configuration[$name] = $value;
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
     * @return array
     */
    public function getConfiguration(): array
    {
        return array_merge(self::DEFAULT, $this->configuration);
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
