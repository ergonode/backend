<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Generator\Step;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\ExporterFile\Infrastructure\Generator\ProductDataExportGeneratorStepInterface;

/**
 */
class ProductAttributeDataExportGeneratorStep implements ProductDataExportGeneratorStepInterface
{
    /**
     * @var
     */
    private $calculator;

    /**
     * @param AbstractProduct $product
     * @param Language        $language
     *
     * @return array
     */
    public function generate(AbstractProduct $product, Language $language): array
    {
        $result = [];
        foreach ($product->getAttributes() as $code => $value) {
             $result[$code] = $this->getValue($value, $language);
        }

        return $result;
    }

    /**
     * @param ValueInterface $value
     * @param Language       $language
     *
     * @return string
     */
    private function getValue(ValueInterface $value, Language $language): string {
        //@todo inheritance calculator
        return 'value';
    }
}