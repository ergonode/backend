<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\ImporterErgonode\Infrastructure\Factory\Product;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Model\ProductModel;

/**
 */
interface ProductCommandFactoryInterface
{
    /**
     * @param string $type
     * @return bool
     */
    public function supports(string $type): bool;

    /**
     * @param Import       $import
     * @param ProductModel $model
     * @return DomainCommandInterface
     */
    public function create(Import $import, ProductModel $model): DomainCommandInterface;
}
