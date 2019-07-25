<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface LogQueryInterface
{
    /**
     * @param UserId|null $id
     *
     * @return DataSetInterface
     */
    public function getDataSet(?UserId $id = null): DataSetInterface;
}
