<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Resolver;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Factory\Product\ProductCommandFactoryInterface;
use Ergonode\ImporterErgonode\Infrastructure\Model\ProductModel;
use Webmozart\Assert\Assert;

/**
 */
final class ProductCommandResolver
{
    /**
     * @var iterable|ProductCommandFactoryInterface[]
     */
    private iterable $commandFactories;

    /**
     * @param iterable|ProductCommandFactoryInterface[] $commandFactories
     */
    public function __construct(iterable $commandFactories)
    {
        Assert::allIsInstanceOf($commandFactories, ProductCommandFactoryInterface::class);
        $this->commandFactories = $commandFactories;
    }

    /**
     * @param Import       $import
     * @param ProductModel $model
     *
     * @return DomainCommandInterface
     *
     * @throws \RuntimeException
     */
    public function resolve(Import $import, ProductModel $model): DomainCommandInterface
    {
        foreach ($this->commandFactories as $commandFactory) {
            if ($commandFactory->supports($model->getType())) {
                return $commandFactory->create($import, $model);
            }
        }

        throw new \RuntimeException(sprintf('Product command by product type "%s" not found', $model->getType()));
    }
}
