<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Resolver;

use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Factory\Product\ProductCommandFactoryInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Model\ProductModel;
use Webmozart\Assert\Assert;

class ProductCommandResolver
{
    private iterable $commandFactories;

    public function __construct(iterable $commandFactories)
    {
        Assert::allIsInstanceOf($commandFactories, ProductCommandFactoryInterface::class);
        $this->commandFactories = $commandFactories;
    }

    /**
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
