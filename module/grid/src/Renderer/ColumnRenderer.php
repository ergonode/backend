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
            $result[] = $this->renderColumn($id, $column, $configuration);
        }

        return $result;
    }

    /**
     * @param string                     $id
     * @param ColumnInterface            $column
     * @param GridConfigurationInterface $gridConfiguration
     *
     * @return array
     */
    public function renderColumn(
        string $id,
        ColumnInterface $column,
        GridConfigurationInterface $gridConfiguration
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
        $result['editable'] = $column->isEditable();
        $result['deletable'] = $column->isDeletable();

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
