<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Domain\Query;

use Ergonode\Attribute\Domain\Entity\AttributeId;

/**
 * Class DbalAttributeTemplatesQuery
 */
interface AttributeTemplateQueryInterface
{
    /**
     * @param AttributeId $id
     *
     * @return array
     */
    public function getDesignTemplatesByAttributeId(AttributeId $id): array;
}
