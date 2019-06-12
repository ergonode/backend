<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
abstract class AbstractGrid
{
    public const PARAMETER_ALLOW_COLUMN_RESIZE = 'allow_column_resize';
    public const PARAMETER_ALLOW_COLUMN_EDIT = 'allow_column_edit';
    public const PARAMETER_ALLOW_COLUMN_MOVE = 'allow_column_move';
    public const CONFIGURATION_SHOW_DATA = 'DATA';
    public const CONFIGURATION_SHOW_COLUMN = 'COLUMN';
    public const CONFIGURATION_SHOW_INFO = 'INFO';
    public const CONFIGURATION_SHOW_CONFIGURATION = 'CONFIGURATION';

    public const DEFAULT = [
        self::PARAMETER_ALLOW_COLUMN_RESIZE => false,
        self::PARAMETER_ALLOW_COLUMN_EDIT => false,
        self::PARAMETER_ALLOW_COLUMN_MOVE => false,
    ];

    /**
     * @var ColumnInterface[]
     */
    protected $columns = [];

    /**
     * @var ActionInterface[]
     */
    private $actions = [];

    /**
     * @var array
     */
    private $configuration = [];

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $order = 'ASC';

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
     * @param DataSetInterface           $data
     * @param GridConfigurationInterface $configuration
     *
     * @param Language                   $language
     *
     * @return array
     */
    public function render(DataSetInterface $data, GridConfigurationInterface $configuration, Language $language): array
    {
        $collection = new ArrayCollection();
        $columns = new ArrayCollection();
        $filters = [];
        $this->init($configuration, $language);

        foreach ($this->columns as $id => $column) {
            $columns->add($this->getConfiguration($id, $column));
            $filter = $column->getFilter();

            if ($filter && array_key_exists($id, $configuration->getFilters())) {
                $filter->setValue($configuration->getFilters()[$id]);
                $filters[$id] = $filter;
            }
        }

        $field = $configuration->getField() ?: $this->field;
        $order = $configuration->getOrder() ?: $this->order;

        $records = $data->getItems($this->columns, $filters, $configuration->getLimit(), $configuration->getOffset(), $field, $order);

        foreach ($records as $row) {
            $collection->add($this->renderRow($row, $this->columns));
        }

        return $this->renderResult($columns, $collection, $filters, $data, $configuration);
    }

    /**
     * @param ArrayCollection            $columns
     * @param ArrayCollection            $collection
     * @param array                      $filters
     * @param DataSetInterface           $dataSet
     * @param GridConfigurationInterface $configuration
     *
     * @return array
     */
    private function renderResult(
        ArrayCollection $columns,
        ArrayCollection $collection,
        array $filters,
        DataSetInterface $dataSet,
        GridConfigurationInterface $configuration
    ): array {
        $result = [];
        if (in_array(self::CONFIGURATION_SHOW_CONFIGURATION, $configuration->getShow(), true)) {
            $result = array_merge(
                $result,
                [
                    'configuration' => array_merge(self::DEFAULT, $this->configuration),
                ]
            );
        }

        if (in_array(self::CONFIGURATION_SHOW_COLUMN, $configuration->getShow(), true)) {
            $result = array_merge(
                $result,
                [
                    'columns' => $columns->toArray(),
                ]
            );
        }
        if (in_array(self::CONFIGURATION_SHOW_DATA, $configuration->getShow(), true)) {
            $result = array_merge(
                $result,
                [
                    'collection' => $collection->toArray(),
                ]
            );
        }

        if (in_array(self::CONFIGURATION_SHOW_INFO, $configuration->getShow(), true)) {
            $result = array_merge(
                $result,
                [
                    'offset' => $configuration->getOffset(),
                    'limit' => $configuration->getLimit(),
                    'count' => $dataSet->countItems(),
                    'filtered' => $dataSet->countItems($filters),
                    'actions' => $this->getActions(),
                ]
            );
        }

        return $result;
    }

    /**
     * @param array             $row
     * @param ColumnInterface[] $columns
     *
     * @return array
     */
    private function renderRow(array $row, array $columns = []): array
    {
        $result = [];
        foreach ($columns as $id => $column) {
            $result[$id] = $column->render($id, $row);
        }

        return $result;
    }

    /**
     * @param string          $id
     * @param ColumnInterface $column
     *
     * @return array
     */
    private function getConfiguration(string $id, ColumnInterface $column): array
    {
        $result = [];
        $result['id'] = $id;
        if ($column->getLanguage()) {
            $result['id'] = sprintf('%s:%s', $column->getField(), $column->getLanguage()->getCode());
        }
        $result['type'] = $column->getType();
        $result['label'] = $column->getLabel();
        $result['visible'] = $column->isVisible();
        if (isset($this->configuration[self::PARAMETER_ALLOW_COLUMN_EDIT]) && $this->configuration[self::PARAMETER_ALLOW_COLUMN_EDIT] === true) {
            $result['editable'] = $column->isEditable();
        } else {
            $result['editable'] = false;
        }

        if ($column->getLanguage()) {
            $result['language'] = $column->getLanguage()->getCode();
        }

        if ($column->getFilter()) {
            $result['filter'] = array_merge(['type' => $column->getFilter()->getType()], $column->getFilter()->render());
        }

        if ($column->getWidth()) {
            $result['width'] = $column->getWidth();
        }

        foreach ($column->getExtensions() as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getActions(): array
    {
        $result = [];
        foreach ($this->actions as $name => $action) {
            $result[] = [
                'label' => $name,
                'type' => $action->getType(),
            ];
        }

        return $result;
    }
}
