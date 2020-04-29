<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Builder;

use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

/**
 */
class ImportProductCategoryBuilder implements ProductImportBuilderInterface
{
    /**
     * @var CategoryQueryInterface
     */
    private CategoryQueryInterface $query;

    /**
     * @param CategoryQueryInterface $query
     */
    public function __construct(CategoryQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param ImportedProduct $product
     * @param Record          $record
     *
     * @return ImportedProduct
     *
     * @throws \Exception
     */
    public function build(ImportedProduct $product, Record $record): ImportedProduct
    {
        $categories = [];
        if ($record->has('esa_categories')) {
            $value = $record->get('esa_categories');

            if ($value instanceof StringValue) {
                $codes = explode(',', $value);
                foreach ($codes as $code) {
                    $categoryId = $this->query->findIdByCode(new CategoryCode($code));
                    if ($categoryId) {
                        $categories[$code] = $categoryId;
                    }
                }
            }
        }

        $product->categories = array_values($categories);

        return $product;
    }
}
