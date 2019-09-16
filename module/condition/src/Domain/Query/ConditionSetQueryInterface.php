<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Query;

use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DbalDataSet;

/**
 */
interface ConditionSetQueryInterface
{
    /**
     * @param Language $language
     *
     * @return DbalDataSet
     */
    public function getDataSet(Language $language): DbalDataSet;

    /**
     * @param ConditionSetCode $conditionSetCode
     *
     * @return bool
     */
    public function isExistsByCode(ConditionSetCode $conditionSetCode): bool;
}
