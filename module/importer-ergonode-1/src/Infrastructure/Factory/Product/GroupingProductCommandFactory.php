<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Product;

use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\Importer\Domain\Command\Import\ImportGroupingProductCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Model\ProductModel;
use Ergonode\Product\Domain\Entity\GroupingProduct;

class GroupingProductCommandFactory implements ProductCommandFactoryInterface
{
    public function supports(string $type): bool
    {
        return GroupingProduct::TYPE === $type;
    }

    public function create(Import $import, ProductModel $model): DomainCommandInterface
    {
        return new ImportGroupingProductCommand(
            $import->getId(),
            $model->getSku(),
            $model->getTemplate(),
            $model->getCategories(),
            [], // @todo children
            $model->getAttributes()
        );
    }
}
