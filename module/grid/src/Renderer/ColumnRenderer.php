<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ColumnRenderer
{
    /**
     * @var FilterRenderer
     */
    private FilterRenderer $filterRenderer;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param FilterRenderer      $filterRenderer
     * @param TranslatorInterface $translator
     */
    public function __construct(FilterRenderer $filterRenderer, TranslatorInterface $translator)
    {
        $this->filterRenderer = $filterRenderer;
        $this->translator = $translator;
    }

    /**
     * @param AbstractGrid               $grid
     * @param GridConfigurationInterface $configuration
     *
     * @return array
     */
    public function render(AbstractGrid $grid, GridConfigurationInterface $configuration): array
    {
        $result = [];
        foreach ($grid->getColumns() as $id => $column) {
            $result[] = $this->renderColumn($id, $column, $configuration, $grid->getConfiguration());
        }

        return $result;
    }

    /**
     * @param string                     $id
     * @param ColumnInterface            $column
     * @param GridConfigurationInterface $gridConfiguration
     * @param array                      $configuration
     *
     * @return array
     */
    public function renderColumn(
        string $id,
        ColumnInterface $column,
        GridConfigurationInterface $gridConfiguration,
        array $configuration
    ): array {
        $result = [];

        if ($column->hasLanguage()) {
            $result['language'] = $column->getLanguage() ? $column->getLanguage()->getCode() : null;
            $result['id'] = sprintf('%s:%s', $column->getField(), $result['language']);
        } else {
            $result['id'] = $id;
        }

        $result['type'] = $column->getType();
        $result['label'] = $column->getLabel() ? $this->translator->trans($column->getLabel(), [], 'grid') : null;
        $result['visible'] = $column->isVisible();

        if (isset($configuration[AbstractGrid::PARAMETER_ALLOW_COLUMN_EDIT]) &&
            $configuration[AbstractGrid::PARAMETER_ALLOW_COLUMN_EDIT] === true
        ) {
            $result['editable'] = $column->isEditable();
        } else {
            $result['editable'] = false;
        }

        if (isset($configuration[AbstractGrid::PARAMETER_ALLOW_COLUMN_EDIT]) &&
            $configuration[AbstractGrid::PARAMETER_ALLOW_COLUMN_EDIT] === true
        ) {
            $result['deletable'] = $column->isDeletable();
        } else {
            $result['deletable'] = false;
        }

        if ($column->getFilter()) {
            $result['filter'] =
                $this
                    ->filterRenderer
                    ->render($column->getField(), $column->getFilter(), $gridConfiguration->getFilters());
        }

        foreach ($column->getExtensions() as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }
}
