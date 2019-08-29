<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductSimple\Infrastructure\Action\Extension;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Webmozart\Assert\Assert;

/**
 */
class ProductCategoryExtension
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $repository;

    /**
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Record $record
     * @param array  $data
     *
     * @return array
     * @throws \Exception
     */
    public function extend(Record $record, array $data): array
    {
        if ($record->hasColumns('categories')) {
            foreach ($record->getColumns('categories') as $key => $value) {
                if ($value instanceof StringValue && !empty($value->getValue())) {
                    $categoryCode = new CategoryCode($value->getValue());
                    $categoryId = CategoryId::fromCode($categoryCode);
                    $category = $this->repository->load($categoryId);
                    Assert::notNull($category);
                    $data['categories'][$value->getValue()] = $category->getCode();

                    foreach ($category->getAttributes() as $code => $attributeValue) {
                        $data['attributes'][$code] = $attributeValue;
                    }
                }
            }
        }

        return $data;
    }
}
