<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

interface SegmentQueryInterface
{
    /**
     * @return array
     */
    public function findIdByConditionSetId(ConditionSetId $conditionSetId): array;

    public function isExistsByCode(SegmentCode $segmentCode): bool;

    /**
     * @return array
     */
    public function getAllSegmentIds(): array;

    /**
     * @return array
     */
    public function getDictionary(): array;

    /**
     * @return array
     */
    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
