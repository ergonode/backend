<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Repository;

use Ergonode\Exporter\Domain\Entity\Catalog\AbstractExportProduct;
use Ramsey\Uuid\Uuid;

/**
 */
interface ProductRepositoryInterface
{
    /**
     * @param Uuid $id
     *
     * @return AbstractExportProduct
     */
    public function load(Uuid $id): ?AbstractExportProduct;

    /**
     * @param AbstractExportProduct $product
     */
    public function save(AbstractExportProduct $product): void;

    /**
     * @param Uuid $id
     *
     * @return bool
     */
    public function exists(Uuid $id): bool;

    /**
     * @param AbstractExportProduct $product
     */
    public function delete(AbstractExportProduct $product): void;
}
