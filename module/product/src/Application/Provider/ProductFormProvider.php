<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Provider;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Application\Form\Product\SimpleProductForm;

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

        throw new \RuntimeException(sprintf('Can\' find factory for %s type', $type));
    }
}
