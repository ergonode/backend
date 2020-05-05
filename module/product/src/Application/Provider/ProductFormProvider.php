<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Provider;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Application\Form\Product\SimpleProductForm;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Application\Form\Product\VariableProductForm;
use Ergonode\Product\Domain\Entity\GroupingProduct;
use Ergonode\Product\Application\Form\Product\GroupingProductForm;

/**
 */
class ProductFormProvider
{
    /**
     * @param string $type
     *
     * @return string
     */
    public function provide(string $type): string
    {
        if (SimpleProduct::TYPE === $type) {
            return SimpleProductForm::class;
        }

        if (VariableProduct::TYPE === $type) {
            return VariableProductForm::class;
        }

        if (GroupingProduct::TYPE === $type) {
            return GroupingProductForm::class;
        }

        throw new \RuntimeException(sprintf('Can\' find factory for %s type', $type));
    }
}
