<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Query;

use Ergonode\Grid\DataSetInterface;

interface TemplateElementQueryInterface
{
    public function getDataSet(): DataSetInterface;
}
