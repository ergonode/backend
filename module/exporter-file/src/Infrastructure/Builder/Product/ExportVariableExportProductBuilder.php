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
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;

class ExportVariableExportProductBuilder implements ExportProductBuilderInterface
{
    private AttributeQueryInterface $attributeQuery;

    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    public function header(): array
    {
        return ['_bindings'];
    }

    public function build(AbstractProduct $product, ExportLineData $result, Language $language): void
    {
        $result->set('_bindings');
        if ($product instanceof VariableProduct) {
            $result->set('_bindings', implode(',', $this->getBindings($product)));
        }
    }

    private function getBindings(VariableProduct $product): array
    {
        $result = [];
        foreach ($product->getBindings() as $bindId) {
            $code = $this->attributeQuery->findAttributeCodeById($bindId);
            Assert::notNull($code, sprintf('Can\'t find code of attribute "%s"', $bindId->getValue()));
            $result[] = $code;
        }

        return $result;
    }
}
