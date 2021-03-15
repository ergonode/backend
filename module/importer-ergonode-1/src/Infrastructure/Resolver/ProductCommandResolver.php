<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Resolver;

use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Factory\Product\ProductCommandFactoryInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Model\ProductModel;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ProductCommandResolver
{
    /**
     * @var ProductCommandFactoryInterface[]
     */
    private iterable $commandFactories;

    public function __construct(iterable $commandFactories)
    {
        Assert::allIsInstanceOf($commandFactories, ProductCommandFactoryInterface::class);
        $this->commandFactories = $commandFactories;
    }

    /**
     * @throws \RuntimeException
     */
    public function resolve(ImportLineId $id, Import $import, ProductModel $model): DomainCommandInterface
    {
        foreach ($this->commandFactories as $commandFactory) {
            if ($commandFactory->supports($model->getType())) {
                return $commandFactory->create($id, $import, $model);
            }
        }

        throw new \RuntimeException(sprintf('Product command by product type "%s" not found', $model->getType()));
    }
}
