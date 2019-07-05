<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Provider\Dictionary;

use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;

/**
 */
class AttributeGroupDictionaryProvider
{
    /**
     * @var AttributeGroupQueryInterface
     */
    private $attributeGroupQuery;

    /**
     * AttributeGroupDictionaryProvider constructor.
     *
     * @param AttributeGroupQueryInterface $attributeGroupQuery
     */
    public function __construct(AttributeGroupQueryInterface $attributeGroupQuery)
    {
        $this->attributeGroupQuery = $attributeGroupQuery;
    }

    /**
     * @return array
     */
    public function getDictionary(): array
    {
        $collection = $this->attributeGroupQuery->getAttributeGroups();
        $result = [];
        foreach ($collection as $element) {
            if (isset($element['id'])) {
                $result[$element['id']] = $element['label'];
            }
        }

        return $result;
    }
}
