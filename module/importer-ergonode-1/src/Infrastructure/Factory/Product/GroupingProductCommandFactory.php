<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\ImporterErgonode\Infrastructure\Factory\Product;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Command\Import\ImportGroupingProductCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Model\ProductModel;
use Ergonode\Product\Domain\Entity\GroupingProduct;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
final class GroupingProductCommandFactory implements ProductCommandFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return GroupingProduct::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function create(Import $import, ProductModel $model): DomainCommandInterface
    {
        return new ImportGroupingProductCommand(
            $import->getId(),
            new Sku($model->getSku()),
            $model->getTemplate(),
            [], // @todo categories
            [], // @todo children
            $model->getAttributes()
        );
    }
}
