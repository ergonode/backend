<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Provider\Dictionary;

use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class AttributeGroupDictionaryProvider
{
    private AttributeGroupQueryInterface $attributeGroupQuery;

    public function __construct(AttributeGroupQueryInterface $attributeGroupQuery)
    {
        $this->attributeGroupQuery = $attributeGroupQuery;
    }

    /**
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        $collection = $this->attributeGroupQuery->getAttributeGroups($language);
        $result = [];
        foreach ($collection as $element) {
            if (isset($element['id'])) {
                $result[$element['id']] = $element['label'];
            }
        }

        return $result;
    }
}
