<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Query;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;

/**
 */
interface SegmentQueryInterface
{
    /**
     * @param Language $language
     *
     * @return DbalDataSet
     */
    public function getDataSet(Language $language): DbalDataSet;

    /**
     * @param ConditionSetId $conditionSetId
     *
     * @return array
     */
    public function findIdByConditionSetId(ConditionSetId $conditionSetId): array;

    /**
     * @param SegmentCode $segmentCode
     *
     * @return bool
     */
    public function isExistsByCode(SegmentCode $segmentCode): bool;

    /**
     * @return array
     */
    public function getAllSegmentIds(): array;
}
