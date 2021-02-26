<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Product;

use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\Importer\Domain\Command\Import\ImportVariableProductCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Model\ProductModel;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class VariableProductCommandFactory implements ProductCommandFactoryInterface
{
    public function supports(string $type): bool
    {
        return VariableProduct::TYPE === $type;
    }

    public function create(ImportLineId $id, Import $import, ProductModel $model): DomainCommandInterface
    {
        return new ImportVariableProductCommand(
            $id,
            $import->getId(),
            $model->getSku(),
            $model->getTemplate(),
            $model->getCategories(),
            [], // @todo bindings
            [], // @todo childrens
            $model->getAttributes()
        );
    }
}
