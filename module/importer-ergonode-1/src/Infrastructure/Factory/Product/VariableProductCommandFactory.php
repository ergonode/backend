<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Product;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Command\Import\ImportVariableProductCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Model\ProductModel;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\ValueObject\Sku;

class VariableProductCommandFactory implements ProductCommandFactoryInterface
{
    public function supports(string $type): bool
    {
        return VariableProduct::TYPE === $type;
    }

    public function create(Import $import, ProductModel $model): DomainCommandInterface
    {
        return new ImportVariableProductCommand(
            $import->getId(),
            new Sku($model->getSku()),
            $model->getTemplate(),
            [], // @todo categories
            [], // @todo bindings
            [], // @todo childrens
            $model->getAttributes()
        );
    }
}
