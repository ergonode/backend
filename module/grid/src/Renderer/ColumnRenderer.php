<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class ColumnRenderer
{
    /**
     * @param ArrayCollection            $columns
     * @param ArrayCollection            $collection
     * @param array                      $filters
     * @param DataSetInterface           $dataSet
     * @param GridConfigurationInterface $configuration
     * @param array                      $defaultConfiguration
     *
     * @return array
     */
    public function render(
        ArrayCollection $columns,
        ArrayCollection $collection,
        array $filters,
        DataSetInterface $dataSet,
        GridConfigurationInterface $configuration,
        array $defaultConfiguration
    ): array {
        $result = [];
        if (in_array(GridConfigurationInterface::CONFIGURATION_SHOW_CONFIGURATION, $configuration->getShow(), true)) {
            $result = array_merge(
                $result,
                [
                    'configuration' => array_merge(GridConfigurationInterface::DEFAULT_PARAMETERS, $defaultConfiguration),
                ]
            );
        }

        if (in_array(GridConfigurationInterface::CONFIGURATION_SHOW_COLUMN, $configuration->getShow(), true)) {
            $result = array_merge(
                $result,
                [
                    'columns' => $columns->toArray(),
                ]
            );
        }
        if (in_array(GridConfigurationInterface::CONFIGURATION_SHOW_DATA, $configuration->getShow(), true)) {
            $result = array_merge(
                $result,
                [
                    'collection' => $collection->toArray(),
                ]
            );
        }

        if (in_array(GridConfigurationInterface::CONFIGURATION_SHOW_INFO, $configuration->getShow(), true)) {
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
}
