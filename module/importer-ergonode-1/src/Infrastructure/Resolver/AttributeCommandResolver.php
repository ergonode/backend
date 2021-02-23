<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Resolver;

use Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute\ImportAttributeCommandFactoryInterface;
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
    public function resolve(string $type): ImportAttributeCommandFactoryInterface
    {
        /** @var ImportAttributeCommandFactoryInterface $factory */
        foreach ($this->commandFactories as $factory) {
            if ($factory->supports($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException("Attribute command factory by type \"$type\" not found");
    }
}
