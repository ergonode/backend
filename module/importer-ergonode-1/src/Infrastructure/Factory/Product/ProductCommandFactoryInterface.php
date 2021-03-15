<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Product;

use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Model\ProductModel;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

interface ProductCommandFactoryInterface
{
    public function supports(string $type): bool;
    public function create(ImportLineId $id, Import $import, ProductModel $model): DomainCommandInterface;
}
