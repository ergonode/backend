<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Repository;

use Ergonode\Exporter\Domain\Entity\AbstractProduct;

/**
 */
interface ProductRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return AbstractProduct
     */
    public function load(string $id): ?AbstractProduct;

    /**
     * @param AbstractProduct $product
     */
    public function save(AbstractProduct $product): void;
}
