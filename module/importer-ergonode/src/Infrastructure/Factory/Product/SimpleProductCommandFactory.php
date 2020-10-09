<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\ImporterErgonode\Infrastructure\Factory\Product;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Command\Import\ImportSimpleProductCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Model\ProductModel;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
final class SimpleProductCommandFactory implements ProductCommandFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return SimpleProduct::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function create(Import $import, ProductModel $model): DomainCommandInterface
    {
        return new ImportSimpleProductCommand(
            $import->getId(),
            new Sku($model->getSku()),
            $model->getTemplate(),
            [], // @todo categories
            $model->getAttributes()
        );
    }
}
