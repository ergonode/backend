<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Resolver;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute\ImportAttributeCommandFactoryInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Model\AttributeModel;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Webmozart\Assert\Assert;

class AttributeCommandResolver
{
    private iterable $commandFactories;

    public function __construct(iterable $commandFactories)
    {
        Assert::allIsInstanceOf($commandFactories, ImportAttributeCommandFactoryInterface::class);
        $this->commandFactories = $commandFactories;
    }

    /**
     * @throws \RuntimeException
     */
    public function resolve(ImportLineId $id, Import $import, AttributeModel $model): DomainCommandInterface
    {
        /** @var ImportAttributeCommandFactoryInterface $commandFactory */
        foreach ($this->commandFactories as $commandFactory) {
            if ($commandFactory->supports($model->getType())) {
                return $commandFactory->create($id, $import, $model);
            }
        }

        throw new \RuntimeException(sprintf('Attribute command by attribute type "%s" not found', $model->getType()));
    }
}
