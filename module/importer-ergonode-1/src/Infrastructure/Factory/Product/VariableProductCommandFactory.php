<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\ImporterErgonode\Infrastructure\Factory\Product;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Command\Import\ImportVariableProductCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Model\ProductModel;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
final class VariableProductCommandFactory implements ProductCommandFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return VariableProduct::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
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
