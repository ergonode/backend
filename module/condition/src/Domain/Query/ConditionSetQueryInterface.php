<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

interface ConditionSetQueryInterface
{
    public function getDataSet(Language $language): DataSetInterface;

    /**
     * @return ConditionSetId[]
     */
    public function findAttributeIdConditionRelations(AttributeId $attributeId): array;

    /**
     * @return ConditionSetId[]
     */
    public function findLanguageConditionRelations(Language $language): array;
}
