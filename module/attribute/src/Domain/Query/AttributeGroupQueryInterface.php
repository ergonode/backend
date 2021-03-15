<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

interface AttributeGroupQueryInterface
{
    /**
     * @return array
     */
    public function getAttributeGroups(Language $language): array;

    /**
     * @return array
     */
    public function getAttributeGroupIds(): array;

    public function getDataSet(Language $language): DataSetInterface;

    /**
     * @return AttributeId[]
     */
    public function getAllAttributes(AttributeGroupId $id): array;

    public function checkAttributeGroupExistsByCode(AttributeGroupCode $code): bool;

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
