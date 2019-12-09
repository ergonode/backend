<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Provider\Dictionary;

use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class AttributeGroupDictionaryProvider
{
    /**
     * @var AttributeGroupQueryInterface
     */
    private $attributeGroupQuery;

    /**
     * @param AttributeGroupQueryInterface $attributeGroupQuery
     */
    public function __construct(AttributeGroupQueryInterface $attributeGroupQuery)
    {
        $this->attributeGroupQuery = $attributeGroupQuery;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        $collection = $this->attributeGroupQuery->getAttributeGroups($language);
        $result = [];
        foreach ($collection as $key => $element) {
            if (isset($element['id'])) {
                $result[$key]['id'] = $element['id'];
                $result[$key]['code'] = $element['code'];
                if (!empty($element['label'])) {
                    $result[$key]['name'] = $element['label'];
                }
            }
        }

        return $result;
    }
}
