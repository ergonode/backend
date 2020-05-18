<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Generator\Step;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\Generator\ProductDataExportGeneratorStepInterface;

/**
 */
class ProductBasicDataExportGeneratorStep implements ProductDataExportGeneratorStepInterface
{
    /**
     * @param AbstractProduct $product
     * @param Language        $language
     * @param array           $record
     *
     * @return array
     */
    public function generate(AbstractProduct $product, Language $language, array $record): array
    {
        return [
            '_sku' => $product->getSku()->getValue(),
            '_id' => $product->getSku()->getValue(),
            '_language' => $language->getCode(),
        ];
    }
}