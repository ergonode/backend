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
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilderInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Query\ProductQueryInterface;

class ExportAssociatedExportProductBuilder implements ExportProductBuilderInterface
{
    private ProductQueryInterface $productQuery;

    public function __construct(ProductQueryInterface $productQuery)
    {
        $this->productQuery = $productQuery;
    }

    public function header(): array
    {
        return ['_children'];
    }

    public function build(AbstractProduct $product, ExportLineData $result, Language $language): void
    {
        $result->set('_children');
        if ($product instanceof AbstractAssociatedProduct) {
            $result->set('_children', implode(',', $this->getChildren($product)));
        }
    }

    private function getChildren(AbstractAssociatedProduct $product): array
    {
        $result = [];
        foreach ($product->getChildren() as $childId) {
            $sku = $this->productQuery->findSkuByProductId($childId);
            Assert::notNull($sku, sprintf('Can\'t find sku of product "%s"', $childId->getValue()));
            $result[] = $sku;
        }

        return $result;
    }
}
