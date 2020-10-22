<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

interface ActionInterface
{
    public function getType(): string;

    public function execute(DataSetInterface $dataSet): void;
}
