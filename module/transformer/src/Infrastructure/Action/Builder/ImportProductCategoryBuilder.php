<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action\Builder;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;

/**
 */
class ImportProductCategoryBuilder implements ProductImportBuilderInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $repository;

    /**
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
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
        if ($record->has('categories')) {
            $value = $record->get('categories');

            if ($value instanceof StringValue) {
                $categoryCode = new CategoryCode($value->getValue());
                $categoryId = CategoryId::fromCode($categoryCode);
                if ($this->repository->exists($categoryId)) {
                    $product->categories[$value->getValue()] = $categoryId;
                }
            }

        }

        return $product;
    }
}
