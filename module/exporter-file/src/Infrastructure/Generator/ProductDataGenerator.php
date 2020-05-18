<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Generator;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class ProductDataGenerator
{
    /**
     * @var ProductDataExportGeneratorStepInterface[]
     */
    private array $steps;

    public function generate(AbstractProduct $product, Language $language): array
    {
        $result = [];
        foreach ($this->steps as $step) {
            $result = array_merge($result, $step->generate($product, $language));
        }

        return $result;
    }
}