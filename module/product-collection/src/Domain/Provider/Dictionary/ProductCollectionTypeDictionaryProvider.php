<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Provider\Dictionary;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeQueryInterface;

/**
 */
class ProductCollectionTypeDictionaryProvider
{
    /**
     * @var ProductCollectionTypeQueryInterface
     */
    private ProductCollectionTypeQueryInterface $collectionTypeQuery;

    /**
     * @param ProductCollectionTypeQueryInterface $collectionTypeQuery
     */
    public function __construct(ProductCollectionTypeQueryInterface $collectionTypeQuery)
    {
        $this->collectionTypeQuery = $collectionTypeQuery;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        $collection = $this->collectionTypeQuery->getCollectionTypes($language);

        $result = [];
        foreach ($collection as $element) {
            if (isset($element['id'])) {
                $result[$element['id']] = $element['label'];
            }
        }

        return $result;
    }
}
