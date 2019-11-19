<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Domain\Query;

use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface AttributeGroupQueryInterface
{
    /**
     * @param Language $language
     *
     * @return array
     */
    public function getAttributeGroups(Language $language): array;

    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface;

    /**
     * @param AttributeGroupCode $code
     *
     * @return bool
     */
    public function checkAttributeGroupExistsByCode(AttributeGroupCode $code): bool;
}
