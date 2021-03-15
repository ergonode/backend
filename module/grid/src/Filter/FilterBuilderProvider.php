<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Filter;

use Ergonode\Grid\Filter\Builder\FilterBuilderInterface;
use Ergonode\Grid\FilterInterface;
use Webmozart\Assert\Assert;

class FilterBuilderProvider
{
    /**
     * @var FilterBuilderInterface[]
     */
    private iterable $builderCollection;

    public function __construct(iterable $builderCollection)
    {
        Assert::allIsInstanceOf($builderCollection, FilterBuilderInterface::class);

        $this->builderCollection = $builderCollection;
    }

    public function provide(FilterInterface $columnFilter): FilterBuilderInterface
    {
        foreach ($this->builderCollection as $filterBuilder) {
            if ($filterBuilder->supports($columnFilter)) {
                return $filterBuilder;
            }
        }
        throw new \RuntimeException(
            sprintf('Can\'t find filter builder for %s filter', $columnFilter->getType())
        );
    }
}
