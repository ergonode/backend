<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Product;

use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\Importer\Domain\Command\Import\ImportSimpleProductCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Model\ProductModel;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class SimpleProductCommandFactory implements ProductCommandFactoryInterface
{
    public function supports(string $type): bool
    {
        return SimpleProduct::TYPE === $type;
    }

    public function create(ImportLineId $id, Import $import, ProductModel $model): DomainCommandInterface
    {
        return new ImportSimpleProductCommand(
            $id,
            $import->getId(),
            $model->getSku(),
            $model->getTemplate(),
            $model->getCategories(),
            $model->getAttributes()
        );
    }
}
