<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface AccountQueryInterface
{
    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;

    /**
     * @param UserId $userId
     *
     * @return array
     */
    public function getUser(UserId $userId): array;
}
