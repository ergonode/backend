<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Form\Product;

/**
 *
 */
interface ProductFormInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;
}
