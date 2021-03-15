<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Product;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilderInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

class ExportProductCategoryBuilder implements ExportProductBuilderInterface
{
    private CategoryQueryInterface $categoryQuery;

    public function __construct(CategoryQueryInterface $categoryQuery)
    {
        $this->categoryQuery = $categoryQuery;
    }

    public function header(): array
    {
        return ['_categories'];
    }

    public function build(AbstractProduct $product, ExportLineData $result, Language $language): void
    {
        $result->set('_categories', $this->buildCategoryCodes($product));
    }

    private function buildCategoryCodes(AbstractProduct $product): string
    {
        $result = [];
        foreach ($product->getCategories() as $categoryId) {
            $result[] = $this->getCategoryCode($categoryId)->getValue();
        }

        return implode(',', $result);
    }

    private function getCategoryCode(CategoryId $categoryId): CategoryCode
    {
        $categoryCode = $this->categoryQuery->findCodeById($categoryId);
        Assert::notNull($categoryCode);

        return $categoryCode;
    }
}
