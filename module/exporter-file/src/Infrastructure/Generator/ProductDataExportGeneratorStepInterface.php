<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Generator;


use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
interface ProductDataExportGeneratorStepInterface
{
    /**
     * @param AbstractProduct $product
     * @param Language        $language
     *
     * @return array
     */
    public function generate(AbstractProduct $product, Language $language): array;
}