<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

/**
 */
interface ActionInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param DataSetInterface $dataSet
     */
    public function execute(DataSetInterface $dataSet): void;
}
