<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\ImporterErgonode\Infrastructure\Resolver;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Factory\Attribute\AttributeCommandFactoryInterface;
use Ergonode\ImporterErgonode\Infrastructure\Model\AttributeModel;
use Webmozart\Assert\Assert;

/**
 */
final class AttributeCommandResolver
{
    /**
     * @var iterable|AttributeCommandFactoryInterface[]
     */
    private iterable $commandFactories;

    /**
     * @param iterable|AttributeCommandFactoryInterface[] $commandFactories
     */
    public function __construct(iterable $commandFactories)
    {
        Assert::allIsInstanceOf($commandFactories, AttributeCommandFactoryInterface::class);
        $this->commandFactories = $commandFactories;
    }

    /**
     * @param Import         $import
     * @param AttributeModel $model
     * @return DomainCommandInterface
     */
    public function resolve(Import $import, AttributeModel $model): DomainCommandInterface
    {
        foreach ($this->commandFactories as $commandFactory) {
            if ($commandFactory->supports($model->getType())) {
                return $commandFactory->create($import, $model);
            }
        }
    }
}
